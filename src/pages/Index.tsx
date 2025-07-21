
import { useEffect } from "react";
import { Link } from "react-router-dom";
import Hero from "@/components/Hero";
import FeatureCard from "@/components/FeatureCard";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { Users, MapPin, Shield, Award, Calendar, Clock, Heart, ArrowRight } from "lucide-react";

const Index = () => {
  // Scroll to top on component mount
  useEffect(() => {
    window.scrollTo(0, 0);
  }, []);

  const bloodTypes = [
    { type: "A+", canDonateTo: ["A+", "AB+"], canReceiveFrom: ["A+", "A-", "O+", "O-"] },
    { type: "A-", canDonateTo: ["A+", "A-", "AB+", "AB-"], canReceiveFrom: ["A-", "O-"] },
    { type: "B+", canDonateTo: ["B+", "AB+"], canReceiveFrom: ["B+", "B-", "O+", "O-"] },
    { type: "B-", canDonateTo: ["B+", "B-", "AB+", "AB-"], canReceiveFrom: ["B-", "O-"] },
    { type: "AB+", canDonateTo: ["AB+"], canReceiveFrom: ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"] },
    { type: "AB-", canDonateTo: ["AB+", "AB-"], canReceiveFrom: ["A-", "B-", "AB-", "O-"] },
    { type: "O+", canDonateTo: ["A+", "B+", "AB+", "O+"], canReceiveFrom: ["O+", "O-"] },
    { type: "O-", canDonateTo: ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"], canReceiveFrom: ["O-"] },
  ];

  return (
    <div className="min-h-screen bg-white dark:bg-gray-950 overflow-hidden">
      <Navbar />

      <main className="animate-fade-in">
        <Hero />

        {/* Features Section */}
        <section className="py-24 px-6 bg-gray-50 dark:bg-gray-900">
          <div className="max-w-7xl mx-auto">
            <div className="text-center max-w-3xl mx-auto mb-16">
              <div className="inline-flex items-center gap-2 bg-ocean/10 text-ocean px-4 py-1.5 rounded-full text-sm font-medium mb-4">
                <Shield className="h-4 w-4" />
                <span>Why choose us</span>
              </div>
              <h2 className="text-3xl md:text-4xl font-bold tracking-tight">
                Making blood donation more accessible
              </h2>
              <p className="text-muted-foreground mt-4">
                Our platform streamlines the blood donation process, connecting donors with recipients efficiently and safely.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
              <FeatureCard
                icon={<Users className="h-6 w-6" />}
                title="Direct Connection"
                description="Connect directly with blood donors or recipients in your area through our secure platform."
              />
              <FeatureCard
                icon={<MapPin className="h-6 w-6" />}
                title="Location-Based Matching"
                description="Find the closest donors or recipients based on your location to minimize travel time."
                className="lg:translate-y-8"
              />
              <FeatureCard
                icon={<Shield className="h-6 w-6" />}
                title="Verified Profiles"
                description="All donors and recipients go through a verification process ensuring safety and reliability."
              />
              <FeatureCard
                icon={<Award className="h-6 w-6" />}
                title="Quality Assurance"
                description="We maintain high standards for blood donation, following all medical guidelines and protocols."
              />
              <FeatureCard
                icon={<Calendar className="h-6 w-6" />}
                title="Scheduled Donations"
                description="Plan and schedule donations in advance, making it easier to commit to regular giving."
                className="lg:translate-y-8"
              />
              <FeatureCard
                icon={<Clock className="h-6 w-6" />}
                title="Emergency Requests"
                description="Critical blood needs are highlighted and prioritized to help save lives in emergencies."
              />
            </div>
          </div>
        </section>

        {/* Blood Compatibility Section */}
        <section className="py-24 px-6">
          <div className="max-w-7xl mx-auto">
            <div className="text-center max-w-3xl mx-auto mb-16">
              <div className="inline-flex items-center gap-2 bg-blood/10 text-blood px-4 py-1.5 rounded-full text-sm font-medium mb-4">
                <Heart className="h-4 w-4" />
                <span>Blood Compatibility</span>
              </div>
              <h2 className="text-3xl md:text-4xl font-bold tracking-tight">
                Understanding blood type compatibility
              </h2>
              <p className="text-muted-foreground mt-4">
                Blood type compatibility is crucial for successful transfusions. Learn which blood types are compatible with yours.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              {bloodTypes.map((bloodType) => (
                <div 
                  key={bloodType.type}
                  className="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-shadow"
                >
                  <div className="w-16 h-16 rounded-full bg-blood/10 flex items-center justify-center mb-4 mx-auto">
                    <span className="text-2xl font-bold text-blood">{bloodType.type}</span>
                  </div>
                  <h3 className="text-center font-semibold text-lg mb-4">Type {bloodType.type}</h3>
                  
                  <div className="space-y-3">
                    <div>
                      <p className="text-sm text-muted-foreground mb-2">Can donate to:</p>
                      <div className="flex flex-wrap gap-2">
                        {bloodType.canDonateTo.map((type) => (
                          <span key={type} className="px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-full text-xs font-medium">
                            {type}
                          </span>
                        ))}
                      </div>
                    </div>
                    <div>
                      <p className="text-sm text-muted-foreground mb-2">Can receive from:</p>
                      <div className="flex flex-wrap gap-2">
                        {bloodType.canReceiveFrom.map((type) => (
                          <span key={type} className="px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-full text-xs font-medium">
                            {type}
                          </span>
                        ))}
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            <div className="mt-12 text-center">
              <Link 
                to="/about" 
                className="inline-flex items-center text-blood hover:text-blood-dark font-medium gap-2 group"
              >
                Learn more about blood donation
                <ArrowRight className="h-4 w-4 group-hover:translate-x-1 transition-transform" />
              </Link>
            </div>
          </div>
        </section>

        {/* CTA Section */}
        <section className="py-24 px-6 bg-gradient-to-br from-blood/90 to-blood-dark text-white">
          <div className="max-w-5xl mx-auto text-center">
            <h2 className="text-3xl md:text-4xl font-bold tracking-tight mb-6">
              Ready to make a difference?
            </h2>
            <p className="text-white/80 text-lg mb-8 max-w-2xl mx-auto">
              Join our community today and help save lives through blood donation. Every donation counts and can save up to three lives.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link 
                to="/register" 
                className="px-8 py-3 bg-white text-blood rounded-full font-medium hover:bg-gray-100 transition-colors shadow-md"
              >
                Register Now
              </Link>
              <Link 
                to="/find-donor" 
                className="px-8 py-3 bg-transparent border border-white text-white rounded-full font-medium hover:bg-white/10 transition-colors"
              >
                Find a Donor
              </Link>
            </div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
};

export default Index;
