
import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { Droplet, MapPin, Search, AlertCircle, UserPlus, Calendar, Clock } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useToast } from "@/components/ui/use-toast";

const Receiver = () => {
  const { toast } = useToast();
  
  // Scroll to top on component mount
  useEffect(() => {
    window.scrollTo(0, 0);
  }, []);

  const [location, setLocation] = useState("");
  const [bloodType, setBloodType] = useState("");
  const [urgency, setUrgency] = useState("normal");
  const [details, setDetails] = useState("");
  
  const bloodTypes = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Here you would typically handle form submission
    console.log("Blood request submitted:", { location, bloodType, urgency, details });
    
    toast({
      title: "Request submitted successfully",
      description: "We are now searching for donors in your area.",
    });
  };

  const potentialDonors = [
    {
      name: "Rahul M.",
      bloodType: "O+",
      distance: "1.5 km",
      lastDonation: "3 months ago",
      available: true
    },
    {
      name: "Priya S.",
      bloodType: "A-",
      distance: "2.3 km",
      lastDonation: "5 months ago",
      available: true
    },
    {
      name: "Ajay K.",
      bloodType: "B+",
      distance: "3.7 km",
      lastDonation: "1 month ago",
      available: false
    },
    {
      name: "Neha T.",
      bloodType: "AB+",
      distance: "4.2 km",
      lastDonation: "7 months ago",
      available: true
    }
  ];

  return (
    <div className="min-h-screen bg-white dark:bg-gray-950">
      <Navbar />
      
      <main className="pt-28 pb-16 px-6 animate-fade-in">
        <div className="max-w-7xl mx-auto">
          <div className="text-center mb-16">
            <div className="inline-flex items-center gap-2 bg-blood/10 text-blood px-4 py-1.5 rounded-full text-sm font-medium mb-4">
              <AlertCircle className="h-4 w-4" />
              <span>Blood Request</span>
            </div>
            <h1 className="text-4xl md:text-5xl font-bold mb-4">Find a Blood Donor</h1>
            <p className="text-muted-foreground max-w-3xl mx-auto">
              Create a request to find suitable blood donors in your area. Our platform connects patients with donors quickly and efficiently.
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
            <div className="lg:col-span-1">
              <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 p-8">
                <h2 className="text-2xl font-bold mb-6">Request Blood</h2>
                <form onSubmit={handleSubmit} className="space-y-6">
                  <div className="space-y-2">
                    <Label htmlFor="location">Your Location</Label>
                    <div className="relative">
                      <MapPin className="absolute left-3 top-3 h-5 w-5 text-muted-foreground" />
                      <Input
                        id="location"
                        placeholder="Enter your city or hospital"
                        className="pl-10"
                        value={location}
                        onChange={(e) => setLocation(e.target.value)}
                        required
                      />
                    </div>
                  </div>
                  
                  <div className="space-y-2">
                    <Label htmlFor="bloodType">Required Blood Type</Label>
                    <select
                      id="bloodType"
                      value={bloodType}
                      onChange={(e) => setBloodType(e.target.value)}
                      className="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50"
                      required
                    >
                      <option value="">Select blood type</option>
                      {bloodTypes.map((type) => (
                        <option key={type} value={type}>{type}</option>
                      ))}
                    </select>
                  </div>
                  
                  <div className="space-y-2">
                    <Label htmlFor="urgency">Urgency Level</Label>
                    <select
                      id="urgency"
                      value={urgency}
                      onChange={(e) => setUrgency(e.target.value)}
                      className="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50"
                      required
                    >
                      <option value="normal">Normal</option>
                      <option value="urgent">Urgent</option>
                      <option value="emergency">Emergency</option>
                    </select>
                  </div>
                  
                  <div className="space-y-2">
                    <Label htmlFor="details">Additional Details</Label>
                    <textarea
                      id="details"
                      placeholder="Patient details, hospital information, etc."
                      className="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50 min-h-[100px]"
                      value={details}
                      onChange={(e) => setDetails(e.target.value)}
                    />
                  </div>
                  
                  <Button type="submit" className="w-full bg-blood hover:bg-blood-dark">
                    Submit Blood Request
                  </Button>
                </form>
                
                <div className="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800">
                  <p className="text-sm text-muted-foreground mb-4">
                    Not registered as a recipient yet?
                  </p>
                  <Link to="/register">
                    <Button variant="outline" className="w-full flex items-center gap-2">
                      <UserPlus className="h-4 w-4" />
                      Register as Recipient
                    </Button>
                  </Link>
                </div>
              </div>
            </div>
            
            <div className="lg:col-span-2">
              <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 p-8">
                <h2 className="text-2xl font-bold mb-6">Potential Donors Near You</h2>
                <div className="mb-6">
                  <div className="relative">
                    <Search className="absolute left-3 top-3 h-5 w-5 text-muted-foreground" />
                    <Input placeholder="Search by blood type or location" className="pl-10" />
                  </div>
                </div>
                
                <div className="space-y-6">
                  {potentialDonors.map((donor, index) => (
                    <div 
                      key={index}
                      className="p-4 border border-gray-100 dark:border-gray-800 rounded-xl hover:shadow-md transition-shadow"
                    >
                      <div className="flex justify-between items-start">
                        <div>
                          <h3 className="font-semibold text-lg">{donor.name}</h3>
                          <div className="flex items-center gap-4 mt-1">
                            <p className="text-sm flex items-center gap-1">
                              <Droplet className="h-4 w-4 text-blood" /> 
                              <span className="font-medium">{donor.bloodType}</span>
                            </p>
                            <p className="text-muted-foreground text-sm flex items-center gap-1">
                              <MapPin className="h-4 w-4" /> {donor.distance}
                            </p>
                          </div>
                        </div>
                        
                        <div className={`px-3 py-1 rounded-full text-xs font-medium ${
                          donor.available 
                            ? "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400" 
                            : "bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400"
                        }`}>
                          {donor.available ? "Available" : "Unavailable"}
                        </div>
                      </div>
                      
                      <div className="mt-4 flex items-center justify-between">
                        <p className="text-xs text-muted-foreground flex items-center gap-1">
                          <Clock className="h-3.5 w-3.5" /> Last donation: {donor.lastDonation}
                        </p>
                        
                        <Button 
                          variant="outline" 
                          size="sm"
                          disabled={!donor.available}
                          className={donor.available ? "text-blood border-blood hover:bg-blood/10" : ""}
                        >
                          Contact Donor
                        </Button>
                      </div>
                    </div>
                  ))}
                </div>
                
                <div className="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800 text-center">
                  <p className="text-sm text-muted-foreground mb-4">
                    Can't find a suitable donor?
                  </p>
                  <Button variant="outline" className="w-full md:w-auto">
                    Expand Search Radius
                  </Button>
                </div>
              </div>
              
              <div className="mt-8 bg-orange-50 dark:bg-amber-950/30 border border-orange-100 dark:border-amber-900/50 rounded-xl p-6">
                <div className="flex items-start gap-4">
                  <div className="bg-orange-100 dark:bg-amber-900/50 p-2 rounded-full text-orange-600 dark:text-amber-400">
                    <AlertCircle className="h-5 w-5" />
                  </div>
                  <div>
                    <h3 className="font-semibold text-orange-800 dark:text-amber-400">Emergency Situations</h3>
                    <p className="text-orange-700 dark:text-amber-300/80 text-sm mt-1">
                      For critical emergencies, please also contact your nearest hospital or blood bank directly. 
                      In life-threatening situations, call emergency services at 108 immediately.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div className="bg-gradient-to-br from-ocean/90 to-ocean-dark text-white rounded-2xl p-8 md:p-12">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
              <div>
                <h2 className="text-2xl md:text-3xl font-bold mb-4">
                  Help Others by Donating
                </h2>
                <p className="text-white/80 max-w-2xl">
                  Already found your donor? Consider becoming a donor yourself to help others in need.
                  One donation can save up to three lives.
                </p>
              </div>
              <Link to="/sender">
                <Button className="bg-white text-ocean hover:bg-gray-100">
                  Become a Donor
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

export default Receiver;
