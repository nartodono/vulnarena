# Infrastructure Deployments

This folder contains all Kubernetes deployment files used throughout the development of Vulnarena using Minikube.

## File Descriptions

- **api-vulnarena.yaml**  
  Deployment manifest for the Vulnarena backend/API service.

- **main-vulnarena.yaml**  
  Deployment manifest for the main Vulnarena frontend website.

- **database-vulnarena.yaml**  
  Deployment manifest for the primary database service used by Vulnarena.

- **dbpvc-vulnarena.yaml**  
  Persistent Volume Claim (PVC) configuration for the database service.

- **phpmyadmin-vulnarena.yaml**  
  Deployment manifest for phpMyAdmin, used for GUI-based database management during development.

- **delete-lab-cronjob.yaml**  
  Kubernetes CronJob for automating dynamic lab deletion.

- **backend-ops-role.yaml**  
  Role definition for creating accounts/privileges for the K8s PHP client when accessing the Minikube API.

- **debug-mysql.yaml**  
  Deployment used for debugging and troubleshooting MySQL during development.

- **debug-pvc.yaml**  
  Manifest used for debugging PVC-related issues.

## Notes

- All files are organized to support development, testing, and automation tasks within the Vulnarena project.
- Please update this documentation if new deployment files are added or changes are made.

