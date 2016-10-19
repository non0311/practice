#!/bin/sh

#httpdインストール
yum -y install httpd

#mariadbインストール
yum -y install mariadb mariadb-server

#epel,remi インストール
yum -y install epel-release
rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm

#phpと必要なモジュールをインストール
yum -y install --enablerepo=remi,epel,remi-php70 php php-intl php-mbstring php-pdo php-mysqlnd

#phpmyadmin
#yum -y install --enablerepo=remi,epel,remi-php70 phpmyadmin
# phpMyAdmin設定
#cat /vagrant/phpMyAdmin.conf > /etc/httpd/conf.d/phpMyAdmin.conf

#その他
yum -y install vim

#httpd設定シンボリックリンク作成
ln -s /vagrant/practice.conf /etc/httpd/conf.d/.

#mariadb起動、自動起動設定
systemctl start mariadb
systemctl enable mariadb

#httpd起動、自動起動設定
systemctl start httpd
systemctl enable httpd
