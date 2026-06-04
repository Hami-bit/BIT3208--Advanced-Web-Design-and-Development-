# Smoke tests: register -> login -> dashboard
$ts = Get-Date -Format yyyyMMddHHmmss
$user = "testuser$ts"
$pass = "Password123!"
$email = "$user@example.com"
$cookie = "C:\\temp\\bank_cookies.txt"
New-Item -Path (Split-Path $cookie) -ItemType Directory -Force | Out-Null

Write-Output "Testing using user: $user"

# Register
$curl = 'C:\\Windows\\System32\\curl.exe'

# Register
$reg = & $curl -s -L -c $cookie -d "first_name=Auto&last_name=Tester&email=$email&phone=+1000000000&account_type=savings&username=$user&password=$pass&confirm_password=$pass" "http://localhost/BankingSystem/Week5/register.php"
if ($reg -match "Account created successfully") { Write-Output "REGISTER: OK" } else { Write-Output "REGISTER: FAIL"; Write-Output $reg }

# Login
$login = & $curl -s -L -c $cookie -b $cookie -d "username=$user&password=$pass" "http://localhost/BankingSystem/Week5/login.php"
if ($login -match "Location: dashboard.php" -or $login -match "Account created successfully") { Write-Output "LOGIN: OK" } else { Write-Output "LOGIN: Check response"; Write-Output $login }

# Fetch dashboard
$dash = & $curl -s -L -b $cookie "http://localhost/BankingSystem/Week5/dashboard.php"
if ($dash -match "Good day") { Write-Output "DASHBOARD: OK" } else { Write-Output "DASHBOARD: FAIL"; Write-Output $dash }
