# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  config.ssh.insert_key = false
  config.ssh.forward_agent = true

  config.vm.box = "bento/centos-7.2"

  config.vm.network "private_network", ip: "192.168.33.10"

# config.vm.synced_folder "./src", "/var/www/html"

  config.vm.provision "shell",:path => "./provision.sh"

end
