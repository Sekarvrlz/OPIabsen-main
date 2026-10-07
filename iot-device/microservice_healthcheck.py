#!/usr/bin/env python3
"""Health check for Presensi IoT services (Laravel Backend & CodeIgniter Frontend)."""

from __future__ import annotations

import os
import sys

import requests

try:
    from dotenv import load_dotenv
except Exception:
    load_dotenv = None


def load_env_file_fallback(path: str = ".env") -> None:
    if not os.path.exists(path):
        return

    with open(path, "r", encoding="utf-8") as fh:
        for line in fh:
            line = line.strip()
            if not line or line.startswith("#") or "=" not in line:
                continue
            key, value = line.split("=", 1)
            key = key.strip()
            value = value.strip().strip("\"' ")
            if key and key not in os.environ:
                os.environ[key] = value


def check(
    url: str,
    headers: dict | None = None,
    timeout: int = 5,
    accepted_statuses: set[int] | None = None,
) -> tuple[bool, str]:
    try:
        resp = requests.get(url, headers=headers, timeout=timeout)
        if accepted_statuses is None:
            ok = 200 <= resp.status_code < 300
        else:
            ok = resp.status_code in accepted_statuses
        return ok, f"HTTP {resp.status_code}"
    except Exception as exc:
        return False, str(exc)


def main() -> int:
    if load_dotenv is not None:
        load_dotenv()
    else:
        load_env_file_fallback(".env")

    device_token = os.getenv("IOT_DEVICE_TOKEN", "orange-pi-zero3-token")
    laravel_iot_health = os.getenv("IOT_HEALTH_URL", "http://127.0.0.1:8000/api/iot/health")
    laravel_api_url = os.getenv("FACE_GATEWAY_URL", "http://127.0.0.1:8000/api/face/landmark")
    laravel_bearer = os.getenv("FACE_GATEWAY_BEARER_TOKEN", "absensiiot2026-token")

    checks = [
        (
            "Laravel IoT API",
            laravel_iot_health,
            {"X-Device-Token": device_token} if device_token else {},
            {200},
        ),
        (
            "Laravel Face API",
            laravel_api_url,
            {"Authorization": f"Bearer {laravel_bearer}"} if laravel_bearer else {},
            {200, 401, 403, 422},
        ),
    ]

    print("=== Presensi IoT Health Check (Laravel API Native) ===")
    failed = 0
    for name, url, headers, accepted_statuses in checks:
        ok, detail = check(url, headers=headers, accepted_statuses=accepted_statuses)
        status = "OK" if ok else "FAIL"
        print(f"[{status}] {name:<18} -> {url} ({detail})")
        if not ok:
            failed += 1

    if failed:
        print(f"\nTotal gagal: {failed}")
        return 1

    print("\nSemua service backend online & siap digunakan.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
