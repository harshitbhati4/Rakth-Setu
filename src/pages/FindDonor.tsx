
import { useState, useEffect } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { useToast } from "@/components/ui/use-toast";
import { Search, MapPin, Filter, Clock, Droplet, Heart, Phone, Mail, ArrowUpRight } from "lucide-react";

interface Donor {
  id: number;
  name: string;
  bloodType: string;
  location: string;
  distance: number;
  lastDonation: string;
  contactEmail: string;
  contactPhone: string;
  isAvailable: boolean;
}

const FindDonor = () => {
  const { toast } = useToast();
  const [loading, setLoading] = useState(false);
  const [searchTerm, setSearchTerm] = useState("");
  const [bloodTypeFilter, setBloodTypeFilter] = useState<string>("");
  const [donors, setDonors] = useState<Donor[]>([]);

  const bloodTypes = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];

  // Mock data for donors
  const mockDonors: Donor[] = [
    {
      id: 1,
      name: "Arjun Sharma",
      bloodType: "A+",
      location: "Delhi, India",
      distance: 2.5,
      lastDonation: "2023-12-10",
      contactEmail: "arjun.sharma@example.com",
      contactPhone: "+91 98765 43210",
      isAvailable: true,
    },
    {
      id: 2,
      name: "Priya Patel",
      bloodType: "O-",
      location: "Mumbai, India",
      distance: 3.7,
      lastDonation: "2023-11-05",
      contactEmail: "priya.patel@example.com",
      contactPhone: "+91 98765 12345",
      isAvailable: true,
    },
    {
      id: 3,
      name: "Rahul Kumar",
      bloodType: "B+",
      location: "Bangalore, India",
      distance: 1.2,
      lastDonation: "2024-01-15",
      contactEmail: "rahul.kumar@example.com",
      contactPhone: "+91 87654 32109",
      isAvailable: false,
    },
    {
      id: 4,
      name: "Sneha Gupta",
      bloodType: "AB+",
      location: "Chennai, India",
      distance: 4.1,
      lastDonation: "2023-09-22",
      contactEmail: "sneha.gupta@example.com",
      contactPhone: "+91 76543 21098",
      isAvailable: true,
    },
    {
      id: 5,
      name: "Vikram Singh",
      bloodType: "A-",
      location: "Hyderabad, India",
      distance: 5.3,
      lastDonation: "2023-10-30",
      contactEmail: "vikram.singh@example.com",
      contactPhone: "+91 65432 10987",
      isAvailable: true,
    },
    {
      id: 6,
      name: "Anjali Desai",
      bloodType: "O+",
      location: "Kolkata, India",
      distance: 6.8,
      lastDonation: "2024-02-05",
      contactEmail: "anjali.desai@example.com",
      contactPhone: "+91 54321 09876",
      isAvailable: true,
    },
  ];

  useEffect(() => {
    // Simulate API call to fetch donors
    setLoading(true);
    setTimeout(() => {
      setDonors(mockDonors);
      setLoading(false);
    }, 1500);
  }, []);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    
    // Simulate API search
    setTimeout(() => {
      const filteredDonors = mockDonors.filter(donor => {
        const matchesSearch = donor.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                            donor.location.toLowerCase().includes(searchTerm.toLowerCase());
        
        const matchesBloodType = bloodTypeFilter ? donor.bloodType === bloodTypeFilter : true;
        
        return matchesSearch && matchesBloodType;
      });
      
      setDonors(filteredDonors);
      setLoading(false);
      
      toast({
        title: "Search completed",
        description: `Found ${filteredDonors.length} donors matching your criteria.`,
        variant: "default",
      });
    }, 1000);
  };

  const handleContactDonor = (donor: Donor) => {
    toast({
      title: "Contact request sent",
      description: `Your request has been sent to ${donor.name}. They will contact you shortly.`,
      variant: "default",
    });
  };

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-950">
      <Navbar />
      
      <main className="pt-28 pb-16 px-6 animate-fade-in">
        <div className="max-w-6xl mx-auto">
          <div className="text-center max-w-3xl mx-auto mb-10">
            <h1 className="text-3xl md:text-4xl font-bold mb-4">Find Blood Donors</h1>
            <p className="text-muted-foreground">
              Search for compatible blood donors near your location. Connect with verified donors to arrange a donation.
            </p>
          </div>

          {/* Search and Filter */}
          <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-100 dark:border-gray-800 p-6 mb-8 animate-fade-up">
            <form onSubmit={handleSearch} className="space-y-6">
              <div className="flex flex-col md:flex-row gap-4">
                <div className="flex-1 relative">
                  <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <Search className="h-5 w-5 text-muted-foreground" />
                  </div>
                  <input
                    type="text"
                    placeholder="Search by location or name"
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50"
                  />
                </div>
                
                <div className="md:w-64 relative">
                  <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <Droplet className="h-5 w-5 text-muted-foreground" />
                  </div>
                  <select
                    value={bloodTypeFilter}
                    onChange={(e) => setBloodTypeFilter(e.target.value)}
                    className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-blood/50 appearance-none"
                  >
                    <option value="">All Blood Types</option>
                    {bloodTypes.map((type) => (
                      <option key={type} value={type}>
                        {type}
                      </option>
                    ))}
                  </select>
                </div>
                
                <button
                  type="submit"
                  className="md:w-40 px-6 py-3 bg-blood text-white rounded-lg hover:bg-blood-dark transition-colors focus:outline-none focus:ring-2 focus:ring-blood focus:ring-opacity-50 disabled:opacity-70 flex items-center justify-center gap-2"
                >
                  <Filter className="h-4 w-4" />
                  Filter
                </button>
              </div>
            </form>
          </div>

          {/* Results */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {loading ? (
              // Loading state
              Array.from({ length: 6 }).map((_, index) => (
                <div key={index} className="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 animate-pulse">
                  <div className="flex items-center space-x-4 mb-4">
                    <div className="h-12 w-12 bg-gray-200 dark:bg-gray-800 rounded-full"></div>
                    <div className="flex-1 space-y-2">
                      <div className="h-4 bg-gray-200 dark:bg-gray-800 rounded w-3/4"></div>
                      <div className="h-3 bg-gray-200 dark:bg-gray-800 rounded w-1/2"></div>
                    </div>
                  </div>
                  <div className="space-y-3">
                    <div className="h-3 bg-gray-200 dark:bg-gray-800 rounded w-full"></div>
                    <div className="h-3 bg-gray-200 dark:bg-gray-800 rounded w-5/6"></div>
                    <div className="h-3 bg-gray-200 dark:bg-gray-800 rounded w-4/6"></div>
                  </div>
                  <div className="mt-6 h-10 bg-gray-200 dark:bg-gray-800 rounded"></div>
                </div>
              ))
            ) : donors.length > 0 ? (
              // Donor cards
              donors.map((donor) => (
                <div 
                  key={donor.id}
                  className="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 transition-all duration-300 hover:shadow-md animate-fade-up"
                  style={{ animationDelay: `${donor.id * 100}ms` }}
                >
                  <div className="flex items-start justify-between mb-4">
                    <div className="flex items-center space-x-4">
                      <div className="relative">
                        <div className="w-12 h-12 bg-blood/10 rounded-full flex items-center justify-center">
                          <span className="text-blood font-bold">{donor.bloodType}</span>
                        </div>
                        {donor.isAvailable ? (
                          <span className="absolute -bottom-1 -right-1 h-4 w-4 bg-green-500 rounded-full border-2 border-white dark:border-gray-900"></span>
                        ) : (
                          <span className="absolute -bottom-1 -right-1 h-4 w-4 bg-gray-400 rounded-full border-2 border-white dark:border-gray-900"></span>
                        )}
                      </div>
                      <div>
                        <h3 className="font-semibold">{donor.name}</h3>
                        <div className="flex items-center text-sm text-muted-foreground">
                          <MapPin className="h-3.5 w-3.5 mr-1" />
                          <span>{donor.location}</span>
                        </div>
                      </div>
                    </div>
                    <div className="bg-gray-100 dark:bg-gray-800 text-xs font-medium rounded-full px-2.5 py-1 flex items-center">
                      <MapPin className="h-3 w-3 mr-1 text-blood" />
                      {donor.distance} km
                    </div>
                  </div>

                  <div className="space-y-3 mb-6">
                    <div className="flex items-center text-sm">
                      <Clock className="h-4 w-4 mr-2 text-muted-foreground" />
                      <span>Last donation: {new Date(donor.lastDonation).toLocaleDateString()}</span>
                    </div>
                    <div className="flex items-center text-sm">
                      <Heart className="h-4 w-4 mr-2 text-muted-foreground" />
                      <span>Blood Type: <span className="font-medium">{donor.bloodType}</span></span>
                    </div>
                    <div className="flex items-center text-sm">
                      <Mail className="h-4 w-4 mr-2 text-muted-foreground" />
                      <span className="truncate">{donor.contactEmail}</span>
                    </div>
                    <div className="flex items-center text-sm">
                      <Phone className="h-4 w-4 mr-2 text-muted-foreground" />
                      <span>{donor.contactPhone}</span>
                    </div>
                  </div>

                  <button
                    onClick={() => handleContactDonor(donor)}
                    disabled={!donor.isAvailable}
                    className="w-full py-2.5 rounded-lg flex items-center justify-center gap-2 transition-colors focus:outline-none focus:ring-2 focus:ring-blood focus:ring-opacity-50 disabled:opacity-60 disabled:cursor-not-allowed
                             bg-blood text-white hover:bg-blood-dark"
                  >
                    Contact Donor
                    <ArrowUpRight className="h-4 w-4" />
                  </button>
                </div>
              ))
            ) : (
              // No results
              <div className="col-span-full bg-white dark:bg-gray-900 rounded-xl p-8 shadow-sm border border-gray-100 dark:border-gray-800 text-center">
                <div className="inline-flex items-center justify-center bg-gray-100 dark:bg-gray-800 w-16 h-16 rounded-full mb-4">
                  <Search className="h-8 w-8 text-muted-foreground" />
                </div>
                <h3 className="text-lg font-semibold mb-2">No donors found</h3>
                <p className="text-muted-foreground max-w-md mx-auto">
                  We couldn't find any donors matching your search criteria. Try adjusting your filters or search term.
                </p>
              </div>
            )}
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
};

export default FindDonor;
