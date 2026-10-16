$source = "e:\Z-pos"
$tempDir = "e:\zpos_update_temp"
$destinationZip = "e:\Z-pos\update_v4.zip"

Write-Host "Creating update zip at $destinationZip..."
if (Test-Path $destinationZip) {
    Remove-Item $destinationZip -Force
}
if (Test-Path $tempDir) {
    Remove-Item -Recurse -Force $tempDir
}
New-Item -ItemType Directory -Path $tempDir | Out-Null

# Get modified and untracked files from git
$gitStatus = git status -s

foreach ($line in $gitStatus) {
    # Extract file path from git status output (starts at index 3)
    if ($line.Length -gt 3) {
        $file = $line.Substring(3).Trim()
        
        # Skip certain files
        if ($file -match '^scratch/' -or $file -match '^update.*\.zip$' -or $file -match 'admin_history\.txt') {
            continue
        }

        $sourcePath = Join-Path $source $file
        $destPath = Join-Path $tempDir $file

        # If it's a directory (git status shows trailing slash for untracked dirs), copy recursively
        if (Test-Path -PathType Container $sourcePath) {
            $destDir = Split-Path $destPath
            if (-not (Test-Path $destDir)) {
                New-Item -ItemType Directory -Path $destDir -Force | Out-Null
            }
            Copy-Item -Path "$sourcePath\*" -Destination $destPath -Recurse -Force
        } 
        # If it's a file
        elseif (Test-Path -PathType Leaf $sourcePath) {
            $destDir = Split-Path $destPath
            if (-not (Test-Path $destDir)) {
                New-Item -ItemType Directory -Path $destDir -Force | Out-Null
            }
            Copy-Item -Path $sourcePath -Destination $destPath -Force
        }
    }
}

Write-Host "Zipping the update files..."
Add-Type -AssemblyName System.IO.Compression.FileSystem
[System.IO.Compression.ZipFile]::CreateFromDirectory($tempDir, $destinationZip)

Write-Host "Cleaning up temp directory..."
Remove-Item -Recurse -Force $tempDir

Write-Host "Done! Update patch saved to $destinationZip"
