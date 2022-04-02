@echo off

kubectl apply -f 1-secret.yaml
ping 127.0.0.1 -n 2 > nul

kubectl apply -f 2-configMap.yaml
ping 127.0.0.1 -n 2 > nul

kubectl apply -f 3-statefulset.yaml
ping 127.0.0.1 -n 2 > nul

kubectl apply -f 4-dbService.yaml
ping 127.0.0.1 -n 2 > nul

kubectl apply -f 5-persistentVolumes.yaml
ping 127.0.0.1 -n 2 > nul

kubectl apply -f 6-deployment.yaml
ping 127.0.0.1 -n 2 > nul

kubectl apply -f 7-service.yaml
ping 127.0.0.1 -n 2 > nul

kubectl apply -f 8-ingress.yaml
