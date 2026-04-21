const BASE = "/api";

export async function startScan(target_url) {
    const res = await fetch(`${BASE}/scan`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ target_url }),
    });

    if (!res.ok) throw new Error("Scan failed");

    return res.json();
}

export async function getScan(id) {
    const res = await fetch(`${BASE}/scan/${id}`);

    if (!res.ok) throw new Error("Fetch failed");

    return res.json();
}