@echo off

kubectl delete -f 8-ingress.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 7-service.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 6-deployment.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 5-persistentVolumes.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 4-dbService.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 3-statefulset.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 2-configMap.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 1-secret.yaml
