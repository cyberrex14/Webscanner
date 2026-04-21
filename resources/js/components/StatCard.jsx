import PropTypes from "prop-types";

export default function StatCard({ title, value, color }) {
    return (
        <div className={`p-5 rounded-xl shadow text-white ${color}`}>
            <p className="text-sm opacity-80">{title}</p>
            <h2 className="text-3xl font-bold mt-2">{value}</h2>
        </div>
    );
}

StatCard.propTypes = {
    title: PropTypes.string.isRequired,
    value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
    color: PropTypes.string.isRequired,
};