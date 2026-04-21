import { useState } from "react";
import PropTypes from "prop-types";

export default function ScanInput({ onStart }) {
    const [url, setUrl] = useState("");
    const [error, setError] = useState("");

    const isValidUrl = (value) => {
        try {
            new URL(value);
            return true;
        } catch {
            return false;
        }
    };

    const handleScan = () => {
        if (!url.trim()) {
            setError("URL tidak boleh kosong");
            return;
        }

        if (!isValidUrl(url)) {
            setError("Format URL tidak valid");
            return;
        }

        setError("");
        onStart?.(url);
    };

    return (
        <div className="flex flex-col gap-2">
            <div className="flex gap-2">
                <input
                    type="text"
                    placeholder="https://target.com"
                    value={url}
                    onChange={(e) => {
                        setUrl(e.target.value);
                        setError("");
                    }}
                    className="flex-1 px-4 py-3 bg-white text-gray-800 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 outline-none"
                />

                <button
                    onClick={handleScan}
                    disabled={!url}
                    className="px-5 py-3 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 disabled:bg-gray-400"
                >
                    Scan
                </button>
            </div>

            {error && (
                <p className="text-red-500 text-sm">{error}</p>
            )}
        </div>
    );
}

ScanInput.propTypes = {
    onStart: PropTypes.func,
};

ScanInput.defaultProps = {
    onStart: () => {},
};