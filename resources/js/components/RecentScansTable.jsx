import PropTypes from "prop-types";

export default function RecentScansTable({ scans = [] }) {
    return (
        <table className="w-full text-sm">
            <thead>
                <tr className="text-gray-600 border-b">
                    <th className="p-2 text-left">Domain</th>
                    <th className="p-2 text-left">Status</th>
                    <th className="p-2 text-left">Issues</th>
                    <th className="p-2 text-left">Risk</th>
                </tr>
            </thead>

            <tbody>
                {scans.length === 0 ? (
                    <tr>
                        <td colSpan="4" className="text-center p-10 text-gray-400">
                            🚀 No scans yet. Start scanning a target.
                        </td>
                    </tr>
                ) : (
                    scans.map((s) => (
                        <tr key={s.id} className="border-b hover:bg-gray-50">
                            <td className="p-2">{s.url}</td>

                            <td className="p-2">
                                <span className={`px-2 py-1 rounded text-white text-xs ${
                                    s.status === "done"
                                        ? "bg-green-500"
                                        : s.status === "scanning"
                                        ? "bg-yellow-500"
                                        : "bg-gray-400"
                                }`}>
                                    {s.status}
                                </span>
                            </td>

                            <td className="p-2">{s.issues}</td>

                            <td className="p-2">
                                <span className={`px-2 py-1 rounded text-white text-xs ${
                                    s.risk === "High"
                                        ? "bg-red-500"
                                        : s.risk === "Medium"
                                        ? "bg-yellow-500"
                                        : "bg-green-500"
                                }`}>
                                    {s.risk}
                                </span>
                            </td>
                        </tr>
                    ))
                )}
            </tbody>
        </table>
    );
}

RecentScansTable.propTypes = {
    scans: PropTypes.array,
};