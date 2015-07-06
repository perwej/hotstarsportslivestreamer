#/bin/bash
echo "paste the link"
read link
last=${link: -1}
folder=$PWD/videos/
livestreamer=""
php hotstarlivestreamer.php "$link"
if [[ "$last" = "c" ]]
then echo "enter the Id of the video (example write 1000021386)"
read id
php hotstarlivestreamer.php "$link" "$id"
echo "write quality (example write 720p)"
read quality
php hotstarlivestreamer.php "$link" "$id" "$quality" "$folder" "$livestreamer"
else
echo "write quality (example write 720p)"
read quality
php hotstarlivestreamer.php "$link" "$quality" "$folder" "$livestreamer"
fi

