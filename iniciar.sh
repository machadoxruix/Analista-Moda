#!/bin/bash
sudo service apache2 start
sudo ln -sf /workspaces/Analista-Moda/* /var/www/html/
echo "✅ Apache iniciado y archivos enlazados"
