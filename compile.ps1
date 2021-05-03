$MacDir = '/Users/hyperfresh/.bitnami/stackman/machines/xampp/volumes/root/htdocs'
$WinDir = 'C:\xampp\htdocs'

if ($IsMacOS) {
    Write-Host "Removing existing data." -ForegroundColor Red
    Remove-Item -Path "$MacDir/*" -Recurse -Force
    Write-Host "Copying new data." -ForegroundColor Yellow
    Copy-Item -Path './' -Destination "$MacDir" -Recurse
    Write-Host "Compiled." -ForegroundColor Green
}

if ($IsWindows) {
    Write-Host "Removing existing data." -ForegroundColor Red
    Remove-Item -Path "$WinDir\*" -Recurse -Force
    Write-Host "Copying new data." -ForegroundColor Yellow
    Copy-Item -Path '.\' -Destination "$WinDir" -Recurse
    Write-Host "Compiled." -ForegroundColor Green
}