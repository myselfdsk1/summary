# этот скрипт поределяет изменения по nginx в конфиге ( хранится отдельно ) и по крону обновляет рабочий конфиг (на случай если девопса нету рядом)

# settings
HOME_DIR="/home/sftpuploaduser/"
SYSTEM_DIR="system"
NGINX_HOME_DIR=${HOME_DIR}${SYSTEM_DIR}"/nginx/config/"
NGINX_HOME_BCKP_DIR=${HOME_DIR}${SYSTEM_DIR}"/nginx/config_backup/"
NGINX_SYSTEM_DIR="/etc/nginx/conf.d/"

CUR_DATETIME=$(date +'%Y%m%d%H%M%S')

# configuration

case "${1}" in
		"nginx" )
cd /home/sftpuploaduser/
mkdir ${NGINX_HOME_BCKP_DIR}${CUR_DATETIME}
echo ${NGINX_HOME_BCKP_DIR}${CUR_DATETIME}/${i}
cp ${NGINX_SYSTEM_DIR}*.conf ${NGINX_HOME_BCKP_DIR}${CUR_DATETIME}/
cp ${NGINX_SYSTEM_DIR}/../*.conf ${NGINX_HOME_BCKP_DIR}${CUR_DATETIME}/
ls -dt ${NGINX_HOME_BCKP_DIR}* | tail -n +6 | xargs rm -R
for i in $(ls ${NGINX_HOME_DIR}); 
	do 
	if (echo ${i} |  grep "default.conf" > /dev/null 2>&1 ); then
		if [ `stat -c %Y ${NGINX_SYSTEM_DIR}$i` -ge `stat -c %Y ${NGINX_HOME_DIR}$i` > /dev/null 2>&1 ]; then
		   echo "$i unchanged"
		else
		   echo "$i refreshing ...";
		   cp ${NGINX_HOME_DIR}$i ${NGINX_SYSTEM_DIR}$i;
		   echo "$i copy new file ...";
		   /usr/sbin/nginx -t &> ${NGINX_SYSTEM_DIR}configchanged.log;
			   if grep 'syntax is ok' ${NGINX_SYSTEM_DIR}configchanged.log > /dev/null 2>&1; then 
					echo 'syntax is ok';
					/usr/sbin/nginx -s reload; 
					rm ${NGINX_SYSTEM_DIR}configchanged.log
			   fi
		fi
	else
		if [ `stat -c %Y ${NGINX_SYSTEM_DIR}/../$i` -ge `stat -c %Y ${NGINX_HOME_DIR}$i` > /dev/null 2>&1 ]; then
		   echo "$i unchanged"
		else
		   echo "$i refreshing ...";
		   cp ${NGINX_HOME_DIR}$i ${NGINX_SYSTEM_DIR}/../$i;
		   echo "$i copy new file ...";
		   /usr/sbin/nginx -t &> ${NGINX_SYSTEM_DIR}configchanged.log;
			   if grep 'syntax is ok' ${NGINX_SYSTEM_DIR}configchanged.log > /dev/null 2>&1; then 
					echo 'syntax is ok';
					/usr/sbin/nginx -s reload; 
					rm ${NGINX_SYSTEM_DIR}configchanged.log
			   fi
		fi
	fi
done;
        ;;
esac
