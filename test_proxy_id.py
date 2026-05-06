#!/usr/bin/env python3
"""Test Indonesia (ID) proxies from ID.txt for connectivity."""

import asyncio
import aiohttp
import time
import sys
import os

PROXY_FILE = os.path.join(os.path.dirname(os.path.abspath(__file__)), "ID.txt")
TEST_URL = "http://httpbin.org/ip"
TIMEOUT_SECONDS = 10
MAX_CONCURRENT = 50
SAMPLE_SIZE = 50  # Test first N proxies; set to None to test all


async def test_proxy(session, proxy_url, index):
    """Test a single proxy and return result dict."""
    proxy_url = proxy_url.strip()
    if not proxy_url:
        return None
    start = time.time()
    try:
        async with session.get(
            TEST_URL,
            proxy=proxy_url,
            timeout=aiohttp.ClientTimeout(total=TIMEOUT_SECONDS),
        ) as resp:
            elapsed = round(time.time() - start, 2)
            body = await resp.text()
            return {
                "index": index,
                "proxy": proxy_url,
                "status": resp.status,
                "time_s": elapsed,
                "alive": resp.status == 200,
                "ip": body.strip()[:80],
            }
    except Exception as e:
        elapsed = round(time.time() - start, 2)
        return {
            "index": index,
            "proxy": proxy_url,
            "status": None,
            "time_s": elapsed,
            "alive": False,
            "error": str(e)[:120],
        }


async def main():
    with open(PROXY_FILE) as f:
        proxies = [line.strip() for line in f if line.strip()]

    total = len(proxies)
    sample = proxies[:SAMPLE_SIZE] if SAMPLE_SIZE else proxies
    count = len(sample)

    print(f"=== Indonesia Proxy Test ===")
    print(f"Total proxies in ID.txt: {total}")
    print(f"Testing: {count} proxies (sample)")
    print(f"Timeout: {TIMEOUT_SECONDS}s | Concurrency: {MAX_CONCURRENT}")
    print(f"Test URL: {TEST_URL}")
    print("=" * 40)

    sem = asyncio.Semaphore(MAX_CONCURRENT)

    async def bounded_test(session, proxy, idx):
        async with sem:
            return await test_proxy(session, proxy, idx)

    connector = aiohttp.TCPConnector(limit=MAX_CONCURRENT, ssl=False)
    async with aiohttp.ClientSession(connector=connector) as session:
        tasks = [bounded_test(session, p, i + 1) for i, p in enumerate(sample)]
        results = await asyncio.gather(*tasks)

    results = [r for r in results if r is not None]
    alive = [r for r in results if r["alive"]]
    dead = [r for r in results if not r["alive"]]

    print(f"\n{'#':<5} {'Status':<8} {'Time(s)':<9} {'Proxy (truncated)':<60}")
    print("-" * 85)
    for r in results:
        status = "OK" if r["alive"] else "FAIL"
        proxy_short = r["proxy"][:58] + ".." if len(r["proxy"]) > 60 else r["proxy"]
        print(f"{r['index']:<5} {status:<8} {r['time_s']:<9} {proxy_short}")

    print("\n" + "=" * 40)
    print(f"ALIVE: {len(alive)}/{count}  |  DEAD: {len(dead)}/{count}")
    if alive:
        avg_time = round(sum(r["time_s"] for r in alive) / len(alive), 2)
        print(f"Avg response time (alive): {avg_time}s")
    print("=" * 40)

    # Write alive proxies to file
    alive_file = os.path.join(os.path.dirname(os.path.abspath(__file__)), "ID_alive.txt")
    with open(alive_file, "w") as f:
        for r in alive:
            f.write(r["proxy"] + "\n")
    print(f"\nAlive proxies saved to: {alive_file}")

    return len(alive), count


if __name__ == "__main__":
    alive, total = asyncio.run(main())
    sys.exit(0 if alive > 0 else 1)
