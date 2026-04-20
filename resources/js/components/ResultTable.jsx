import PropTypes from 'prop-types';
import SeverityBadge from "./SeverityBadge";

export default function ResultTable({ results }) {
  if (!results.length) return <p>No vulnerabilities found</p>;

  return (
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Severity</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        {results.map((r, i) => (
          <tr key={r.id}>
            <td>{r.type}</td>
            <td><SeverityBadge level={r.severity} /></td>
            <td>{r.description}</td>
          </tr>
        ))}
      </tbody>
    </table>
  );
}

ResultTable.propTypes = {
  results: PropTypes.array.isRequired,
};
