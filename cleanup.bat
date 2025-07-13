@echo off
echo ========================================
echo E-Prescription System - Cleanup Script
echo ========================================
echo.

echo [INFO] Cleaning up unnecessary files...

REM Remove development files
if exist node_modules rmdir /s /q node_modules
if exist .git rmdir /s /q .git
if exist .vscode rmdir /s /q .vscode
if exist .idea rmdir /s /q .idea

REM Remove cache files
if exist bootstrap\cache\*.php del /q bootstrap\cache\*.php
if exist storage\framework\cache\* del /q storage\framework\cache\*
if exist storage\framework\sessions\* del /q storage\framework\sessions\*
if exist storage\framework\views\* del /q storage\framework\views\*
if exist storage\logs\*.log del /q storage\logs\*.log

REM Remove temporary files
if exist *.tmp del /q *.tmp
if exist *.temp del /q *.temp
if exist *.log del /q *.log
if exist *.cache del /q *.cache

REM Remove backup files
if exist *.bak del /q *.bak
if exist *.backup del /q *.backup

REM Remove OS files
if exist .DS_Store del /q .DS_Store
if exist Thumbs.db del /q Thumbs.db

REM Remove IDE files
if exist *.swp del /q *.swp
if exist *.swo del /q *.swo
if exist *~ del /q *~

REM Remove database files
if exist *.sqlite del /q *.sqlite
if exist *.sqlite3 del /q *.sqlite3
if exist *.db del /q *.db

REM Remove deployment files
if exist deployment rmdir /s /q deployment
if exist eprescription-*.zip del /q eprescription-*.zip
if exist eprescription-*.tar.gz del /q eprescription-*.tar.gz

REM Remove compiled files
if exist *.compiled del /q *.compiled
if exist *.min.js del /q *.min.js
if exist *.min.css del /q *.min.css

echo.
echo [SUCCESS] Cleanup completed!
echo [INFO] Project size optimized for Git deployment
echo.
echo [INFO] Next steps:
echo 1. Run: git init
echo 2. Run: git add .
echo 3. Run: git commit -m "Initial commit"
echo 4. Run: git remote add origin YOUR_REPOSITORY_URL
echo 5. Run: git push -u origin main
echo.
pause 