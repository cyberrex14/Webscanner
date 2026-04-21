export default function Navbar() {
    return (
        <div className="bg-white border-b px-6 py-4 flex justify-between items-center">

            <input
                placeholder="Search for domains..."
                className="w-1/2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            />

            <div className="flex items-center gap-4 text-gray-600">
                <span>🔔</span>
                <span>👤 User</span>
            </div>
        </div>
    );
}