$source = "e:\Z-pos"
$destinationZip = "e:\zpos_cpanel_ready.zip"

Write-Host "Creating zip file at $destinationZip..."
if (Test-Path $destinationZip) {
    Remove-Item $destinationZip -Force
}

# Add-Type for Zip archiving
Add-Type -AssemblyName System.IO.Compression.FileSystem

# Temporarily copy the contents we need to a temp directory
$tempDir = "e:\zpos_temp_build"
if (Test-Path $tempDir) {
    Remove-Item -Recurse -Force $tempDir
}
New-Item -ItemType Directory -Path $tempDir | Out-Null

Write-Host "Copying files to temporary build directory (excluding node_modules and .git)..."
# We exclude large development directories to make the zip smaller and faster
Get-ChildItem -Path $source -Exclude 'node_modules', '.git', 'zpos_cpanel_ready.zip' | 
    Copy-Item -Destination $tempDir -Recurse -Force

Write-Host "Zipping the build directory..."
[System.IO.Compression.ZipFile]::CreateFromDirectory($tempDir, $destinationZip)

Write-Host "Cleaning up temp directory..."
Remove-Item -Recurse -Force $tempDir

Write-Host "Done! File saved to $destinationZip"
