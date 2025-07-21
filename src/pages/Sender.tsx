
import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { Droplet, MapPin, Search, Calendar, UserPlus } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

const Sender = () => {
  // Scroll to top on component mount
  useEffect(() => {
    window.scrollTo(0, 0);
  }, []);

  const [location, setLocation] = useState("");
  const [bloodType, setBloodType] = useState("");
  const [availableDate, setAvailableDate] = useState("");
  
  const bloodTypes = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Here you would typically handle form submission
    console.log("Donation offer submitted:", { location, bloodType, availableDate });
    // You could also add a toast notification here
  };

  const donationCenters = [
    {
      name: "City Hospital Blood Center",
      location: "123 Main Street, City Center",
      distance: "2.3 km",
      needsUrgent: ["A+", "O-", "B-"]
    },
    {
      name: "Red Cross Donation Drive",
      location: "45 Park Avenue, Downtown",
      distance: "3.8 km",
      needsUrgent: ["AB+", "O+"]
    },
    {
      name: "Regional Blood Bank",
      location: "789 Health Road, Westside",
      distance: "5.1 km",
      needsUrgent: ["A-", "AB-"]
    },
    {
      name: "Community Medical Center",
      location: "567 Hospital Drive, Northside",
      distance: "7.4 km",
      needsUrgent: ["B+", "O-"]
    }
  ];

  return (
    <div className="min-h-screen bg-white dark:bg-gray-950">
      <Navbar />
      
      <main className="pt-28 pb-16 px-6 animate-fade-in">
        <div className="max-w-7xl mx-auto">
          <div className="text-center mb-16">
            <div className="inline-flex items-center gap-2 bg-blood/10 text-blood px-4 py-1.5 rounded-full text-sm font-medium mb-4">
              <Droplet className="h-4 w-4" />
              <span>Blood Donation</span>
            </div>
            <h1 className="text-4xl md:text-5xl font-bold mb-4">Become a Donor</h1>
            <p className="text-muted-foreground max-w-3xl mx-auto">
              Your blood donation can save up to three lives. Register as a donor and help those in need by offering your blood for donation.
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
            <div className="lg:col-span-1">
              <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 p-8">
                <h2 className="text-2xl font-bold mb-6">Offer to Donate</h2>
                <form onSubmit={handleSubmit} className="space-y-6">
                  <div className="space-y-2">
                    <Label htmlFor="location">Your Location</Label>
                    <div className="relative">
                      <MapPin className="absolute left-3 top-3 h-5 w-5 text-muted-foreground" />
                      <Input
                        id="location"
                        placeholder="Enter your city or area"
                        className="pl-10"
                        value={location}
                        onChange={(e) => setLocation(e.target.value)}
                        required
                      />
                    </div>
                  </div>
                  
                  <div className="space-y-2">
                    <Label htmlFor="bloodType">Blood Type</Label>
                    <select
                      id="bloodType"
                      value={bloodType}
                      onChange={(e) => setBloodType(e.target.value)}
                      className="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50"
                      required
                    >
                      <option value="">Select your blood type</option>
                      {bloodTypes.map((type) => (
                        <option key={type} value={type}>{type}</option>
                      ))}
                    </select>
                  </div>
                  
                  <div className="space-y-2">
                    <Label htmlFor="availableDate">Available Date</Label>
                    <div className="relative">
                      <Calendar className="absolute left-3 top-3 h-5 w-5 text-muted-foreground" />
                      <Input
                        id="availableDate"
                        type="date"
                        className="pl-10"
                        value={availableDate}
                        onChange={(e) => setAvailableDate(e.target.value)}
                        required
                      />
                    </div>
                  </div>
                  
                  <Button type="submit" className="w-full bg-blood hover:bg-blood-dark">
                    Submit Donation Offer
                  </Button>
                </form>
                
                <div className="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800">
                  <p className="text-sm text-muted-foreground mb-4">
                    Not registered as a donor yet?
                  </p>
                  <Link to="/register">
                    <Button variant="outline" className="w-full flex items-center gap-2">
                      <UserPlus className="h-4 w-4" />
                      Register as Donor
                    </Button>
                  </Link>
                </div>
              </div>
            </div>
            
            <div className="lg:col-span-2">
              <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 p-8">
                <h2 className="text-2xl font-bold mb-6">Nearby Donation Centers</h2>
                <div className="mb-6">
                  <div className="relative">
                    <Search className="absolute left-3 top-3 h-5 w-5 text-muted-foreground" />
                    <Input placeholder="Search donation centers" className="pl-10" />
                  </div>
                </div>
                
                <div className="space-y-6">
                  {donationCenters.map((center, index) => (
                    <div 
                      key={index}
                      className="p-4 border border-gray-100 dark:border-gray-800 rounded-xl hover:shadow-md transition-shadow"
                    >
                      <div className="flex justify-between items-start">
                        <div>
                          <h3 className="font-semibold text-lg">{center.name}</h3>
                          <p className="text-muted-foreground text-sm flex items-center gap-1 mt-1">
                            <MapPin className="h-4 w-4" /> {center.location}
                          </p>
                        </div>
                        <div className="bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-full text-sm">
                          {center.distance}
                        </div>
                      </div>
                      
                      <div className="mt-4">
                        <p className="text-sm font-medium mb-2">Urgent needs:</p>
                        <div className="flex flex-wrap gap-2">
                          {center.needsUrgent.map((type) => (
                            <span 
                              key={type} 
                              className="px-3 py-1 bg-blood/10 text-blood rounded-full text-xs font-medium"
                            >
                              {type}
                            </span>
                          ))}
                        </div>
                      </div>
                      
                      <div className="mt-4 flex justify-end">
                        <Button variant="outline" size="sm">
                          Get Directions
                        </Button>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
          
          <div className="bg-gradient-to-br from-blood/90 to-blood-dark text-white rounded-2xl p-8 md:p-12">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
              <div>
                <h2 className="text-2xl md:text-3xl font-bold mb-4">
                  Every Donation Counts
                </h2>
                <p className="text-white/80 max-w-2xl">
                  Regular donors ensure a stable blood supply for patients in need. Your continued support is crucial for maintaining adequate blood reserves.
                </p>
              </div>
              <Link to="/about">
                <Button className="bg-white text-blood hover:bg-gray-100">
                  Learn More
                </Button>
              </Link>
            </div>
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
};

export default Sender;
