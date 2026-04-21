import PropTypes from "prop-types";
import SeverityBadge from "./SeverityBadge";

export default function ResultTable({ results }) {
    if (!results || results.length === 0) {
        return <p className="text-gray-400 mt-4">No results yet</p>;
    }

    return (
        <div className="mt-6 overflow-x-auto">
            <table className="w-full text-sm">
                <thead>
                    <tr className="text-gray-400 border-b border-gray-700">
                        <th className="p-2 text-left">Type</th>
                        <th className="p-2 text-left">Severity</th>
                        <th className="p-2 text-left">Description</th>
                    </tr>
                </thead>
                <tbody>
                    {results.map((r, i) => (
                        <tr key={i} className="border-b border-gray-800">
                            <td className="p-2">{r.type}</td>
                            <td className="p-2">
                                <SeverityBadge level={r.severity} />
                            </td>
                            <td className="p-2">{r.description}</td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}

ResultTable.propTypes = {
    results: PropTypes.array,
};
