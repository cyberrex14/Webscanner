import PropTypes from 'prop-types';

export default function SeverityBadge({ level }) {
  const colors = {
    low: "green",
    medium: "orange",
    high: "red"
  };

  return (
    <span style={{ color: colors[level] || "gray" }}>
      {level}
    </span>
  );
}

SeverityBadge.propTypes = {
  level: PropTypes.string.isRequired,
};
