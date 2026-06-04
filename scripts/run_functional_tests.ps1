$curl = 'C:\Windows\System32\curl.exe'
$mysql = 'c:\xampp\mysql\bin\mysql.exe'
$ts = Get-Date -Format yyyyMMddHHmmss
$userA = "funcA_$ts"
$userB = "funcB_$ts"
$pass = "Password123!"
$emailA = "$userA@example.com"
$emailB = "$userB@example.com"
$cookieA = "C:\\temp\\funcA_cookie.txt"
$cookieB = "C:\\temp\\funcB_cookie.txt"
New-Item -Path (Split-Path $cookieA) -ItemType Directory -Force | Out-Null

Write-Output "Registering users: $userA and $userB"
# Register A
$regA = & $curl -s -L -c $cookieA -d "first_name=Func&last_name=UserA&email=$emailA&phone=+1000000000&account_type=savings&username=$userA&password=$pass&confirm_password=$pass" "http://localhost/BankingSystem/Week5/register.php"
if ($regA -match "Account created successfully") { Write-Output "Register A: OK" } else { Write-Output "Register A: FAIL"; Write-Output $regA; exit 1 }

# Register B
$regB = & $curl -s -L -c $cookieB -d "first_name=Func&last_name=UserB&email=$emailB&phone=+1000000001&account_type=savings&username=$userB&password=$pass&confirm_password=$pass" "http://localhost/BankingSystem/Week5/register.php"
if ($regB -match "Account created successfully") { Write-Output "Register B: OK" } else { Write-Output "Register B: FAIL"; Write-Output $regB; exit 1 }

# Login A
$loginA = & $curl -s -L -c $cookieA -b $cookieA -d "username=$userA&password=$pass" "http://localhost/BankingSystem/Week5/login.php"
Write-Output "Login A: done"

# Deposit 1000 for A
$deposit = & $curl -s -L -b $cookieA -d "amount=1000&description=TestDeposit" "http://localhost/BankingSystem/Week5/deposit.php"
if ($deposit -match "deposited successfully") { Write-Output "Deposit: OK" } else { Write-Output "Deposit: FAIL"; Write-Output $deposit; exit 1 }

# Query balance A
$balA = & $mysql -u root -sN -e "SELECT balance FROM week5db.users WHERE username='$userA'"
$balA = $balA.Trim()
Write-Output "Balance A after deposit: $balA"
if ([decimal]$balA -ne 1000) { Write-Output "Balance mismatch after deposit"; exit 1 }

# Withdraw 200 from A
$withdraw = & $curl -s -L -b $cookieA -d "amount=200&description=TestWithdraw" "http://localhost/BankingSystem/Week5/withdraw.php"
if ($withdraw -match "withdrawn successfully") { Write-Output "Withdraw: OK" } else { Write-Output "Withdraw: FAIL"; Write-Output $withdraw; exit 1 }

$balA2 = & $mysql -u root -sN -e "SELECT balance FROM week5db.users WHERE username='$userA'"
$balA2 = $balA2.Trim()
Write-Output "Balance A after withdraw: $balA2"
if ([decimal]$balA2 -ne 800) { Write-Output "Balance mismatch after withdraw"; exit 1 }

# Get account number of B
$accB = & $mysql -u root -sN -e "SELECT account_number FROM week5db.users WHERE username='$userB'"
$accB = $accB.Trim()
Write-Output "Account B: $accB"
if (-not $accB) { Write-Output "Failed to find account number for B"; exit 1 }

# Transfer 300 from A to B
$transfer = & $curl -s -L -b $cookieA -d "receiver_account=$accB&amount=300&note=TestTransfer" "http://localhost/BankingSystem/Week5/transfer.php"
if ($transfer -match "transferred to") { Write-Output "Transfer: OK" } else { Write-Output "Transfer: FAIL"; Write-Output $transfer; exit 1 }

# Check balances
$balA3 = & $mysql -u root -sN -e "SELECT balance FROM week5db.users WHERE username='$userA'"
$balB1 = & $mysql -u root -sN -e "SELECT balance FROM week5db.users WHERE username='$userB'"
$balA3 = $balA3.Trim(); $balB1 = $balB1.Trim()
Write-Output "Final Balance A: $balA3"; Write-Output "Final Balance B: $balB1"
if ([decimal]$balA3 -ne 500 -or [decimal]$balB1 -ne 300) { Write-Output "Final balance mismatch"; exit 1 }

Write-Output "All functional tests passed." 
