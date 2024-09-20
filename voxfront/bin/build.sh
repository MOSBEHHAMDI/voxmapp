npm run build
rm -rf platforms plugins
cordova platform add android@12.0.0
export ANDROID_SDK_ROOT=/var/www/android_sdk
export PATH=$PATH:$ANDROID_SDK_ROOT/platform-tools:$ANDROID_SDK_ROOT/tools
export PATH=$PATH:/opt/gradle/gradle-7.5/bin
if [ "$1" = "prod" ]
then
  cordova build --release android
  jarsigner -verbose -keystore certificates/boycott.keystore platforms/android/app/build/outputs/bundle/release/app-release.aab -storepass __BoyC0TT**123 boycott
  rm -f boycott.aab
  cp platforms/android/app/build/outputs/bundle/release/app-release.aab voxmapp.aab
else
  cordova build android
fi
