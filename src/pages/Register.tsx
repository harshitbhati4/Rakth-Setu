
import { useState } from "react";
import { Link } from "react-router-dom";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { ArrowLeft, CheckCircle2 } from "lucide-react";
import { useToast } from "@/components/ui/use-toast";

const Register = () => {
  const { toast } = useToast();
  const [step, setStep] = useState(1);
  const [role, setRole] = useState<"donor" | "recipient" | null>(null);
  const [loading, setLoading] = useState(false);

  const bloodTypes = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    
    // Simulate API call
    setTimeout(() => {
      setLoading(false);
      setStep(3); // Move to success step
      toast({
        title: "Registration successful!",
        description: "Your account has been created.",
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

          {/* Step 1: Choose Role */}
          {step === 1 && (
            <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 p-8 animate-fade-up">
              <h1 className="text-2xl font-bold mb-6">Join LifeStream</h1>
              <p className="text-muted-foreground mb-8">
                Choose how you want to use our platform. You can always change this later.
              </p>

              <div className="space-y-4">
                <button
                  onClick={() => {
                    setRole("donor");
                    setStep(2);
                  }}
                  className={`w-full p-6 text-left rounded-xl border transition-all hover:border-blood focus:outline-none focus:ring-2 focus:ring-blood focus:ring-opacity-50 ${
                    role === "donor" ? "border-blood bg-blood/5" : "border-gray-200 dark:border-gray-800"
                  }`}
                >
                  <h3 className="font-semibold text-lg mb-2">I want to donate blood</h3>
                  <p className="text-muted-foreground text-sm">
                    Register as a donor and help save lives by donating blood to those in need.
                  </p>
                </button>

                <button
                  onClick={() => {
                    setRole("recipient");
                    setStep(2);
                  }}
                  className={`w-full p-6 text-left rounded-xl border transition-all hover:border-blood focus:outline-none focus:ring-2 focus:ring-blood focus:ring-opacity-50 ${
                    role === "recipient" ? "border-blood bg-blood/5" : "border-gray-200 dark:border-gray-800"
                  }`}
                >
                  <h3 className="font-semibold text-lg mb-2">I need blood</h3>
                  <p className="text-muted-foreground text-sm">
                    Register as a recipient to find blood donors matching your requirements.
                  </p>
                </button>
              </div>
            </div>
          )}

          {/* Step 2: Registration Form */}
          {step === 2 && (
            <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 p-8 animate-fade-up">
              <h1 className="text-2xl font-bold mb-2">
                Register as a {role === "donor" ? "Donor" : "Recipient"}
              </h1>
              <p className="text-muted-foreground mb-8">
                Please provide your information to create your account.
              </p>

              <form onSubmit={handleSubmit} className="space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div className="space-y-2">
                    <label htmlFor="firstName" className="text-sm font-medium">
                      First Name
                    </label>
                    <input
                      id="firstName"
                      type="text"
                      className="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50"
                      required
                    />
                  </div>
                  <div className="space-y-2">
                    <label htmlFor="lastName" className="text-sm font-medium">
                      Last Name
                    </label>
                    <input
                      id="lastName"
                      type="text"
                      className="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50"
                      required
                    />
                  </div>
                </div>

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
                  <label htmlFor="password" className="text-sm font-medium">
                    Password
                  </label>
                  <input
                    id="password"
                    type="password"
                    className="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50"
                    required
                  />
                </div>

                <div className="space-y-2">
                  <label htmlFor="phone" className="text-sm font-medium">
                    Phone Number
                  </label>
                  <input
                    id="phone"
                    type="tel"
                    className="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50"
                    required
                  />
                </div>

                <div className="space-y-2">
                  <label htmlFor="bloodType" className="text-sm font-medium">
                    Blood Type
                  </label>
                  <select
                    id="bloodType"
                    className="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50"
                    required
                  >
                    <option value="">Select your blood type</option>
                    {bloodTypes.map((type) => (
                      <option key={type} value={type}>
                        {type}
                      </option>
                    ))}
                  </select>
                </div>

                <div className="space-y-2">
                  <label htmlFor="location" className="text-sm font-medium">
                    Location
                  </label>
                  <input
                    id="location"
                    type="text"
                    placeholder="City, State"
                    className="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50"
                    required
                  />
                </div>

                <div className="flex items-start space-x-3 pt-2">
                  <input
                    id="terms"
                    type="checkbox"
                    className="mt-1 h-4 w-4 rounded border-gray-300 text-blood focus:ring-blood"
                    required
                  />
                  <label htmlFor="terms" className="text-sm text-muted-foreground">
                    I agree to the <Link to="#" className="text-blood hover:underline">Terms of Service</Link> and <Link to="#" className="text-blood hover:underline">Privacy Policy</Link>
                  </label>
                </div>

                <div className="pt-2 flex gap-4">
                  <button
                    type="button"
                    onClick={() => setStep(1)}
                    className="px-6 py-2 border border-gray-200 dark:border-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                  >
                    Back
                  </button>
                  <button
                    type="submit"
                    disabled={loading}
                    className="flex-1 px-6 py-2 bg-blood text-white rounded-lg hover:bg-blood-dark transition-colors focus:outline-none focus:ring-2 focus:ring-blood focus:ring-opacity-50 disabled:opacity-70"
                  >
                    {loading ? "Processing..." : "Create Account"}
                  </button>
                </div>
              </form>

              <div className="mt-8 text-center">
                <p className="text-sm text-muted-foreground">
                  Already have an account?{" "}
                  <Link to="/login" className="text-blood hover:underline">
                    Sign in
                  </Link>
                </p>
              </div>
            </div>
          )}

          {/* Step 3: Success */}
          {step === 3 && (
            <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 p-8 text-center animate-fade-up">
              <div className="inline-flex items-center justify-center bg-green-100 dark:bg-green-900/30 w-16 h-16 rounded-full mb-6">
                <CheckCircle2 className="h-8 w-8 text-green-600 dark:text-green-400" />
              </div>
              
              <h1 className="text-2xl font-bold mb-4">Registration Successful!</h1>
              <p className="text-muted-foreground mb-8">
                Your account has been created successfully. You can now log in to your account.
              </p>

              <div className="space-y-4">
                <Link
                  to="/login"
                  className="block w-full py-3 bg-blood text-white rounded-lg hover:bg-blood-dark transition-colors"
                >
                  Log in to your account
                </Link>
                <Link
                  to="/"
                  className="block w-full py-3 border border-gray-200 dark:border-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                >
                  Return to home page
                </Link>
              </div>
            </div>
          )}
        </div>
      </main>

      <Footer />
    </div>
  );
};

export default Register;
