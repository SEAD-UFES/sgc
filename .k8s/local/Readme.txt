== On server ==
1- Install docker and docker-compose (https://docs.docker.com/get-docker/ and https://docs.docker.com/compose/install/)
2- Copy 'docker-compose.yaml' to a directory of your choice
3- Run 'sudo docker-compose up' from within the chosen directory
4- Save the generated 'kubeconfig.yaml' file

== On workstation ==

5- Install kubectl (https://kubernetes.io/docs/tasks/tools/) and add the directory to system path
6- Use the saved 'kubeconfig.yaml' to config kubectl (copy to '%userprofile%\.kube' on Windows or '~/.kube' on Linux)

7- Fill <APP_KEY> in file './kubectl/2-configMap.yaml' with a Laravel APP_KEY ($ php artisan key:generate) and save
8- Fill <HOST_IP> in file './kubectl/6-service.yaml' with server's IP address ($ ip addr show) and save
9- Run the script './kubectl/sgc-test_apply'
10- Run 'kubectl get pods' and copy the SGC's pod name
11- Run 'kubectl exec <SGC_POD_NAME> -c sgc -it -- "ash"'
12- Inside the pod, run 'php81 artisan migrate:fresh --seed' and afterwards 'exit' when the seeding is complete

13- Browse http://localhost