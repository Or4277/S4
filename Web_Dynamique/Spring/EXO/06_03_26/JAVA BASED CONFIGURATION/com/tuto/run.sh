#!/usr/bin/env bash
set -euo pipefail

# Directory of this script (com/tuto)
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# project root is two levels above com/tuto -> Spring
PROJECT_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"
LIB_DIR="$PROJECT_ROOT/com/lib"

echo "Compiling sources..."
javac -cp "$LIB_DIR/*" -d "$PROJECT_ROOT" "$PROJECT_ROOT/com/tuto"/*.java

echo "Running application..."
java -cp "$PROJECT_ROOT:$LIB_DIR/*" com.tuto.Main

