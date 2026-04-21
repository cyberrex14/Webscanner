export default function Sidebar() {
    return (
        <div className="w-64 bg-gray-900 text-white p-5 space-y-6">

            <h1 className="text-xl font-bold">BellisSec</h1>

            <nav className="flex flex-col gap-3 text-sm">
                <a href="#" className="hover:text-blue-400">Dashboard</a>
                <a href="#" className="hover:text-blue-400">Scans</a>
                <a href="#" className="hover:text-blue-400">Reports</a>
                <a href="#" className="hover:text-blue-400">Settings</a>
            </nav>

        </div>
    );
}