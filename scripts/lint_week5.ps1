$php = 'c:\xampp\php\php.exe'
Get-ChildItem -Path 'c:\xampp\htdocs\BankingSystem\Week5' -Recurse -Filter '*.php' | ForEach-Object {
    $file = $_.FullName
    Write-Output "Linting: $file"
    & $php -l $file
}
