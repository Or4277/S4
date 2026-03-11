#! /bin/bash




echo "compilena aloha "
javac -cp  "lib/*" -d  bin  com/tuto/*.java 

java -cp "bin:lib/*" com.tuto.Main 













