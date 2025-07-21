
import { useState } from "react";
import { Link } from "react-router-dom";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { ArrowLeft, Heart } from "lucide-react";
import { useToast } from "@/components/ui/use-toast";

const Login = () => {
  const { toast } = useToast();
  const [loading, setLoading] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    
    // Simulate API call
    setTimeout(() => {
      setLoading(false);
      toast({
        title: "Login successful!",
        description: "Welcome back to LifeStream.",
        variant: "default",
      });
    }, 1500);
  };

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-950">
      <Navbar />
      
      <main className="pt-28 pb-16 px-6 animate-fade-in">
        <div className="max-w-md mx-auto">
          <div className="mb-8">
            <Link to="/" className="inline-flex items-center text-muted-foreground hover:text-foreground transition-colors">
              <ArrowLeft className="h-4 w-4 mr-2" />
              Back to home
            </Link>
          </div>

          <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 p-8 animate-fade-up">
            <div className="flex justify-center mb-6">
              <div className="relative">
                <div className="w-14 h-14 bg-blood/10 rounded-full flex items-center justify-center">
                  <Heart className="h-7 w-7 text-blood" />
                </div>
                <span className="absolute -bottom-1 -right-1 w-6 h-6 bg-blood text-white text-xs font-bold flex items-center justify-center rounded-full">
                  LS
                </span>
              </div>
            </div>
            
            <h1 className="text-2xl font-bold text-center mb-2">
              Welcome back
            </h1>
            <p className="text-muted-foreground text-center mb-8">
              Sign in to your LifeStream account
            </p>

            <form onSubmit={handleSubmit} className="space-y-6">
              <div className="space-y-2">
                <label htmlFor="email" className="text-sm font-medium">
                  Email
                </label>
                <input
                  id="email"
                  type="email"
                  className="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50"
                  required
                />
              </div>

              <div className="space-y-2">
                <div className="flex items-center justify-between">
                  <label htmlFor="password" className="text-sm font-medium">
                    Password
                  </label>
                  <Link to="#" className="text-xs text-blood hover:underline">
                    Forgot password?
                  </Link>
                </div>
                <input
                  id="password"
                  type="password"
                  className="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50"
                  required
                />
              </div>

              <div className="flex items-start space-x-3">
                <input
                  id="remember"
                  type="checkbox"
                  className="mt-1 h-4 w-4 rounded border-gray-300 text-blood focus:ring-blood"
                />
                <label htmlFor="remember" className="text-sm text-muted-foreground">
                  Remember me for 30 days
                </label>
              </div>

              <button
                type="submit"
                disabled={loading}
                className="w-full px-6 py-3 bg-blood text-white rounded-lg hover:bg-blood-dark transition-colors focus:outline-none focus:ring-2 focus:ring-blood focus:ring-opacity-50 disabled:opacity-70"
              >
                {loading ? "Signing in..." : "Sign in"}
              </button>
            </form>

            <div className="mt-8 text-center">
              <p className="text-sm text-muted-foreground">
                Don't have an account?{" "}
                <Link to="/register" className="text-blood hover:underline">
                  Register now
                </Link>
              </p>
            </div>
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
};

export default Login;
