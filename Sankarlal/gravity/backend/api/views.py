from rest_framework import viewsets, permissions, status
from .models import Patient, MedicationRecord
from .serializers import PatientSerializer, MedicationRecordSerializer
from django.contrib.auth.models import User
from rest_framework.response import Response
from rest_framework.views import APIView
from rest_framework_simplejwt.tokens import RefreshToken
from django.contrib.auth import authenticate

# Patient CRUD (if you use it)
class PatientViewSet(viewsets.ModelViewSet):
    queryset = Patient.objects.all()
    serializer_class = PatientSerializer

# User Registration
class RegisterView(APIView):
    def post(self, request):
        username = request.data.get('username')
        email = request.data.get('email')
        password = request.data.get('password')
        # Optionally handle role here if needed
        if User.objects.filter(username=username).exists():
            return Response({'error': 'Username already exists'}, status=status.HTTP_400_BAD_REQUEST)
        user = User.objects.create_user(username=username, email=email, password=password)
        return Response({'message': 'User created successfully'}, status=status.HTTP_201_CREATED)

# User Login
class LoginView(APIView):
    def post(self, request):
        username = request.data.get('username')
        password = request.data.get('password')
        user = authenticate(username=username, password=password)
        if user is not None:
            refresh = RefreshToken.for_user(user)
            return Response({
                'refresh': str(refresh),
                'access': str(refresh.access_token),
                # Optionally add user info here
            })
        return Response({'error': 'Invalid credentials'}, status=status.HTTP_401_UNAUTHORIZED)

# Medication Record CRUD
class MedicationRecordViewSet(viewsets.ModelViewSet):
    serializer_class = MedicationRecordSerializer
    permission_classes = [permissions.IsAuthenticated]

    def get_queryset(self):
        return MedicationRecord.objects.filter(user=self.request.user)

    def perform_create(self, serializer):
        serializer.save(user=self.request.user)
