#!/usr/bin/env bash
set -euo pipefail

dotnet build .
dotnet run --project src/App
