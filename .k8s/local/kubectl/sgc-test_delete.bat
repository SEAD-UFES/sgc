@echo off

kubectl delete -f 7-ingress.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 6-service.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 5-deployment.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 4-dbService.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 3-statefulset.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 2-configMap.yaml
ping 127.0.0.1 -n 2 > nul

kubectl delete -f 1-secret.yaml
