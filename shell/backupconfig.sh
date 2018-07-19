# settings

BASE_PATH="/home/sftpuploaduser"
STAND="system"
BACKUP_PATH="/u01/backup"
CUR_DATETIME=$(date +'%Y%m%d%H%M%S')


# configuration

case "${1}" in
		"backup" )
date
echo "backup"
zip -r ${BACKUP_PATH}/${STAND}_${CUR_DATETIME}.zip ${BASE_PATH}/${STAND} >/dev/null 2>&1;
ls -dt ${BACKUP_PATH}/${STAND}* | tail -n +16 | xargs rm 
echo
echo
echo
        ;;
esac 