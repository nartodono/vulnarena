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
- All of these deployment files have been tested and work in my environment. If you would like to use them, please review and adjust the configuration as needed to fit your own infrastructure setup.
- And if you're using either the main-vulnarena.yaml or api-vulnarena.yaml please read the Manual Pod Setup section below

## Manual Pod Setup (Development Note)

> **Note:**  
> Currently, the backend and frontend containers are built using the base `buildpack-deps:bookworm` image for flexibility during development and debugging.
>
> All required packages (Apache, PHP, Supervisor, etc.) are **installed manually inside the running pod** using shell commands. This approach is temporary and used as a workaround for current image build issues. The manual setup allows quick adjustments and fixes during active development.
>
> **How to set up:**
> After the pod is running, exec into the container using ```bash kubectl exec -it {pod-name} -n {namespace where the pod in} -- /bin/sh ``` and run the following commands:
>
> **Backend:**
> ```bash
> apt-get update \
>   && apt-get install -y apache2 php libapache2-mod-php php-mysqli curl unzip \
>   && a2enmod rewrite \
>   && sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
>   && service apache2 restart \
>   && apt install -y supervisor \
>   && cat <<EOF > /etc/supervisor/conf.d/vulnarena-ws.conf
> [program:vulnarena-ws]
> command=php /var/www/html/api/pvp/ws/pvpWs.php
> autostart=true
> autorestart=true
> stderr_logfile=/var/log/vulnarena-ws.err.log
> stdout_logfile=/var/log/vulnarena-ws.out.log
> EOF
>   && supervisord -c /etc/supervisor/supervisord.conf
> ```
>
> **Frontend:**
> ```bash
> apt-get update \
>   && apt-get install -y apache2 php curl unzip \
>   && a2enmod rewrite \
>   && sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
>   && echo "Environment setup OK" \
>   && service apache2 restart
> ```
>
> This process should be replaced with a proper custom Docker image in future production deployments.


