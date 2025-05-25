export default function AppLogo() {
    const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

    return (
        <>
            <div className="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-md">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    className="lucide lucide-bot-message-square-icon lucide-bot-message-square text-white dark:text-black"
                >
                    <path d="M12 6V2H8" />
                    <path d="m8 18-4 4V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2Z" />
                    <path d="M2 12h2" />
                    <path d="M9 11v2" />
                    <path d="M15 11v2" />
                    <path d="M20 12h2" />
                </svg>
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-none font-semibold">{appName}</span>
            </div>
        </>
    );
}
