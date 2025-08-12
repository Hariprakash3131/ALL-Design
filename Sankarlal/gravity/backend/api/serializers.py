from rest_framework import serializers
from .models import Patient, MedicationRecord
from rest_framework.viewsets import ModelViewSet
from rest_framework.permissions import IsAuthenticated

class PatientSerializer(serializers.ModelSerializer):
    class Meta:
        model = Patient
        fields = '__all__'

class MedicationRecordSerializer(serializers.ModelSerializer):
    class Meta:
        model = MedicationRecord
        fields = ['id', 'user', 'date', 'taken', 'proof_photo']

    def create(self, validated_data):
        validated_data['user'] = self.context['request'].user
        return super().create(validated_data)

class MedicationRecordViewSet(ModelViewSet):
    serializer_class = MedicationRecordSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return MedicationRecord.objects.filter(user=self.request.user)

    def perform_create(self, serializer):
        serializer.save(user=self.request.user)
