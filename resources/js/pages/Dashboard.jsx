import { useState, useRef, useEffect } from "react";
import StatCard from "../components/StatCard";
import ScanInput from "../components/ScanInput";
import RecentScansTable from "../components/RecentScansTable";
import { startScan, getScan } from "../services/api";

export default function Dashboard() {
    const [scans, setScans] = useState([]);
    const intervalRef = useRef(null);

    const handleStart = async (url) => {
        try {
            const res = await startScan(url);
            poll(res.scan_id);
        } catch (err) {
            console.error(err);
        }
    };

    const poll = (id) => {
        intervalRef.current = setInterval(async () => {
            try {
                const data = await getScan(id);

                if (data.status === "done") {
                    setScans((prev) => [
                        {
                            id: data.id,
                            url: data.target_url,
                            status: data.status,
                            issues: data.results?.length || 0,
                            risk:
                                data.results?.length > 10
                                    ? "High"
                                    : data.results?.length > 5
                                    ? "Medium"
                                    : "Low",
                        },
                        ...prev,
                    ]);

                    clearInterval(intervalRef.current);
                }
            } catch {
                clearInterval(intervalRef.current);
            }
        }, 3000);
    };

    useEffect(() => {
        return () => clearInterval(intervalRef.current);
    }, []);

    return (
        <div className="space-y-6">

            <h1 className="text-2xl font-semibold text-gray-800">
                Dashboard
            </h1>

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {/* LEFT */}
                <div className="lg:col-span-8 space-y-6">

                    <div className="bg-white p-5 rounded-xl shadow min-h-[300px]">
                        <h2 className="font-semibold mb-4 text-gray-700">
                            Recent Scans
                        </h2>

                        <RecentScansTable scans={scans} />
                    </div>

                </div>

                {/* RIGHT */}
                <div className="lg:col-span-4 space-y-6">

                    <div className="bg-white p-4 rounded-xl shadow">
                        <ScanInput onStart={handleStart} />
                    </div>

                    <div className="grid grid-cols-3 gap-4">
                        <StatCard
                            title="Total"
                            value={scans.length}
                            color="bg-blue-500"
                        />
                        <StatCard
                            title="Critical"
                            value="12"
                            color="bg-red-500"
                        />
                        <StatCard
                            title="High"
                            value="24"
                            color="bg-yellow-500"
                        />
                    </div>

                </div>
            </div>
        </div>
    );
}
