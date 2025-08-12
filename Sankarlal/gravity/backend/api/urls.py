from django.urls import path, include
from rest_framework.routers import DefaultRouter
from .views import PatientViewSet, RegisterView, LoginView, MedicationRecordViewSet
from django.contrib.auth.models import User
from .models import Profile
from django.contrib import admin
from django.conf import settings
from django.conf.urls.static import static

router = DefaultRouter()
router.register(r'patients', PatientViewSet)
router.register(r'medication-records', MedicationRecordViewSet, basename='medicationrecord')

urlpatterns = [
    path('api/', include(router.urls)),
    path('register/', RegisterView.as_view(), name='register'),
    path('login/', LoginView.as_view(), name='login'),
] + static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)

admin.site.register(Profile)
