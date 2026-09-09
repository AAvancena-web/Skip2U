#!/usr/bin/env bash
# Regenerate the stylesheet from the standalone design and package the plugin.
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
PLUGIN="$ROOT/plugin/skip2u-redesign"

python3 "$PLUGIN/build-css.py" "$ROOT/index.html" "$PLUGIN/assets/css/s2u.css"
cat "$PLUGIN/assets/css/extra.css" >> "$PLUGIN/assets/css/s2u.css"

cd "$ROOT/plugin"
rm -f "$ROOT/skip2u-redesign.zip"
zip -rq "$ROOT/skip2u-redesign.zip" skip2u-redesign \
  -x '*.DS_Store' '*/build.sh' '*/build-css.py' '*/assets/css/extra.css'
echo "built $ROOT/skip2u-redesign.zip"
