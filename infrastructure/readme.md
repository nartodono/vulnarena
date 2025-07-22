this folder containing all deployment that has been used during the development of Vulnarena
using Minikube (Kubernetes)

api-vulnarena.yaml containing deployment that used by the backend/api
main-vulnarena.yaml containing deployment that used by the main website or frontend
database-vulnarena.yaml containing deployment that used by the database service
dbpvc-vulnarena.yaml containing configuration to create PVC volume for Database
phpmyadmin-vulnarena.yaml containing deployment that used by the phpmyadmin service for GUI database configuration during development
delete-lab-cronjob.yaml containing deployment used for automation for dynamic lab deletion
backend-ops-role.yaml used to create account/previllege for K8S PHP CLIENT accessing the minikube API
debug-*.yaml used for debug for error
