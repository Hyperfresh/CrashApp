$Dir = '/Users/hyperfresh/.bitnami/stackman/machines/xampp/volumes/root/htdocs'

if ($IsMacOS) {
    Write-Host "Removing existing data." -ForegroundColor Red
    Remove-Item -Path "$Dir/*" -Recurse -Force
    Write-Host "Copying new data." -ForegroundColor Yellow
    Copy-Item -Path './' -Destination "$Dir" -Recurse
    Write-Host "Compiled." -ForegroundColor Green
}