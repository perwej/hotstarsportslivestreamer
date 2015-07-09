#/bin/bash
echo "paste the link"
read link
folder=$PWD/videos/
livestreamer=""
php starsportslivestreamer.php "$link" "$livestreamer"
echo "write quality (example write 720p)"
read quality
php starsportslivestreamer.php "$link" "$quality" "$folder" "$livestreamer"
