from django.db import models
from django.contrib.auth.models import User
from rest_framework import serializers
from rest_framework import status
from rest_framework.response import Response
from rest_framework import viewsets, permissions

class Patient(models.Model):
    name = models.CharField(max_length=100)
    age = models.IntegerField()
    # Add more fields as needed

class Profile(models.Model):
    ROLE_CHOICES = (
        ('patient', 'Patient'),
        ('caretaker', 'Caretaker'),
    )
    user = models.OneToOneField(User, on_delete=models.CASCADE)
    role = models.CharField(max_length=20, choices=ROLE_CHOICES)

    def __str__(self):
        return f"{self.user.username} - {self.role}"

class MedicationRecord(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    date = models.DateField()
    taken = models.BooleanField(default=False)
    proof_photo = models.ImageField(upload_to='proof_photos/', null=True, blank=True)

    class Meta:
        unique_together = ('user', 'date')

    def __str__(self):
        return f"{self.user.username} - {self.date} - {'Taken' if self.taken else 'Missed'}"

class MedicationRecordSerializer(serializers.ModelSerializer):
    class Meta:
        model = MedicationRecord
        fields = ['id', 'date', 'taken', 'proof_photo']

    def create(self, validated_data):
        validated_data['user'] = self.context['request'].user
        return super().create(validated_data)

class MedicationRecordViewSet(viewsets.ModelViewSet):
    serializer_class = MedicationRecordSerializer
    permission_classes = [permissions.IsAuthenticated]

    def get_queryset(self):
        return MedicationRecord.objects.filter(user=self.request.user)

    def create(self, request, *args, **kwargs):
        user = request.user
        date = request.data.get('date')
        if MedicationRecord.objects.filter(user=user, date=date).exists():
            return Response(
                {"detail": "Record for this user and date already exists."},
                status=status.HTTP_400_BAD_REQUEST
            )
        return super().create(request, *args, **kwargs)