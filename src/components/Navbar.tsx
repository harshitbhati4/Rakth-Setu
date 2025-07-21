
import { useState, useEffect } from "react";
import { Link, useLocation } from "react-router-dom";
import { cn } from "@/lib/utils";
import { Menu, X, Heart, Droplet } from "lucide-react";

const Navbar = () => {
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const location = useLocation();

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 10);
    };

    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  useEffect(() => {
    setIsMobileMenuOpen(false);
  }, [location.pathname]);

  const navLinks = [
    { name: "Home", path: "/" },
    { name: "Donate Blood", path: "/sender" },
    { name: "Find Donor", path: "/receiver" },
    { name: "About", path: "/about" },
  ];

  return (
    <nav
      className={cn(
        "fixed top-0 w-full z-50 transition-all duration-300 px-6 py-4",
        isScrolled
          ? "bg-white/80 dark:bg-black/80 backdrop-blur-md shadow-sm"
          : "bg-transparent"
      )}
    >
      <div className="max-w-7xl mx-auto flex items-center justify-between">
        {/* Logo */}
        <Link
          to="/"
          className="flex items-center gap-2 text-blood hover:opacity-90 transition-opacity"
        >
          <Heart className="h-6 w-6 fill-blood stroke-blood animate-pulse-soft" />
          <span className="font-bold text-xl tracking-tight">Rakth Setu</span>
        </Link>

        {/* Desktop Navigation */}
        <div className="hidden md:flex items-center gap-8">
          {navLinks.map((link) => (
            <Link
              key={link.path}
              to={link.path}
              className={cn(
                "font-medium transition-all hover:text-blood relative px-2",
                location.pathname === link.path
                  ? "text-blood"
                  : "text-foreground",
                "group"
              )}
            >
              {link.name}
              <span className={cn(
                "absolute bottom-0 left-0 w-0 h-0.5 bg-blood transition-all duration-300 group-hover:w-full",
                location.pathname === link.path ? "w-full" : ""
              )} />
            </Link>
          ))}
        </div>

        {/* Auth Buttons */}
        <div className="hidden md:flex items-center gap-4">
          <Link
            to="/login"
            className="px-4 py-2 font-medium transition-all hover:text-blood"
          >
            Log in
          </Link>
          <Link
            to="/register"
            className="px-6 py-2 bg-blood text-white rounded-full hover:bg-blood-dark transition-colors shadow-sm"
          >
            Register
          </Link>
        </div>

        {/* Mobile Menu Button */}
        <button
          className="md:hidden text-foreground p-1"
          onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
          aria-label={isMobileMenuOpen ? "Close menu" : "Open menu"}
        >
          {isMobileMenuOpen ? (
            <X className="h-6 w-6" />
          ) : (
            <Menu className="h-6 w-6" />
          )}
        </button>
      </div>

      {/* Mobile Menu */}
      <div
        className={cn(
          "fixed inset-0 bg-white/95 dark:bg-black/95 z-40 flex flex-col pt-20 pb-6 px-6 md:hidden transition-transform duration-300 ease-in-out",
          isMobileMenuOpen ? "translate-y-0" : "-translate-y-full"
        )}
      >
        <div className="flex flex-col space-y-6">
          {navLinks.map((link) => (
            <Link
              key={link.path}
              to={link.path}
              className={cn(
                "text-xl font-medium py-2 border-b border-gray-100 dark:border-gray-800",
                location.pathname === link.path ? "text-blood" : "text-foreground"
              )}
            >
              {link.name}
            </Link>
          ))}
        </div>
        <div className="mt-auto pt-6 flex flex-col gap-4">
          <Link
            to="/login"
            className="w-full py-3 text-center font-medium border border-gray-200 dark:border-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors"
          >
            Log in
          </Link>
          <Link
            to="/register"
            className="w-full py-3 text-center font-medium bg-blood text-white rounded-lg hover:bg-blood-dark transition-colors"
          >
            Register
          </Link>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
