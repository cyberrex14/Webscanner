import PropTypes from "prop-types";

export default function SeverityBadge({ level }) {
    const colors = {
        high: "bg-red-500",
        medium: "bg-yellow-500",
        low: "bg-green-500",
    };

    return (
        <span className={`px-2 py-1 text-xs rounded ${colors[level] || "bg-gray-500"}`}>
            {level}
        </span>
    );
}

SeverityBadge.propTypes = {
    level: PropTypes.oneOf(["high", "medium", "low"]).isRequired,
};
