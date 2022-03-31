#!/bin/bash

kubectl apply -f 1-secret.yaml
sleep 2

kubectl apply -f 2-configMap.yaml
sleep 2

kubectl apply -f 3-statefulset.yaml
sleep 2

kubectl apply -f 4-dbService.yaml
sleep 2

kubectl apply -f 5-deployment.yaml
sleep 2

kubectl apply -f 6-service.yaml
sleep 2

kubectl apply -f 7-ingress.yaml
