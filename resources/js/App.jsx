import Sidebar from "./components/Sidebar";
import Navbar from "./components/Navbar";
import Dashboard from "./pages/Dashboard";

export default function App() {
    return (
        <div className="flex min-h-screen bg-gray-100">

            {/* SIDEBAR */}
            <Sidebar />

            {/* MAIN */}
            <div className="flex-1 flex flex-col min-w-0">

                <Navbar />

                <main className="flex-1 overflow-auto p-6">
                    <Dashboard />
                </main>

            </div>
        </div>
    );
}
