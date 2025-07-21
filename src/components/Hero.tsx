
import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Droplet, MapPin, Users, Clock } from 'lucide-react';
import { cn } from '@/lib/utils';

const Hero = () => {
  const [loaded, setLoaded] = useState(false);

  useEffect(() => {
    setLoaded(true);
  }, []);

  return (
    <div className="relative min-h-screen overflow-hidden bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-black px-6 pt-32 pb-20 flex flex-col items-center justify-center">
      {/* Background Elements */}
      <div className="absolute inset-0 overflow-hidden">
        <div className="absolute -top-40 -left-40 w-80 h-80 bg-blood/10 rounded-full filter blur-3xl opacity-60 animate-pulse-soft"></div>
        <div className="absolute -bottom-20 -right-20 w-80 h-80 bg-ocean/10 rounded-full filter blur-3xl opacity-60 animate-pulse-soft" style={{ animationDelay: '2s' }}></div>
      </div>

      {/* Content Container */}
      <div className="max-w-7xl mx-auto relative z-10 w-full">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          {/* Left Column - Text */}
          <div className={cn(
            "flex flex-col space-y-8 transition-all duration-700 ease-out",
            loaded ? "opacity-100 translate-x-0" : "opacity-0 -translate-x-20"
          )}>
            <div className="space-y-2">
              <div className="inline-flex items-center gap-2 bg-blood/10 text-blood px-4 py-1.5 rounded-full text-sm font-medium">
                <Droplet className="h-4 w-4 stroke-blood" />
                <span>Every drop matters</span>
              </div>
              <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight leading-tight">
                Connect to save <span className="text-blood">lives</span> through blood donation
              </h1>
              <p className="text-lg md:text-xl text-muted-foreground mt-4 max-w-xl">
                Join our community of donors and recipients to make blood donation accessible, efficient, and life-saving.
              </p>
            </div>

            <div className="flex flex-col sm:flex-row gap-4">
              <Link 
                to="/register" 
                className="px-8 py-3 bg-blood hover:bg-blood-dark text-white rounded-full font-medium transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1 text-center"
              >
                Become a Donor
              </Link>
              <Link 
                to="/find-donor" 
                className="px-8 py-3 bg-white hover:bg-gray-50 border border-gray-200 rounded-full font-medium transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-1 text-center"
              >
                Find a Donor
              </Link>
            </div>
          </div>

          {/* Right Column - Cards */}
          <div className={cn(
            "relative h-[500px] transition-all duration-700 ease-out",
            loaded ? "opacity-100 translate-x-0" : "opacity-0 translate-x-20"
          )}>
            {/* Stats Card */}
            <div className="absolute top-0 left-0 glass rounded-3xl p-6 w-64 shadow-lg transform hover:-translate-y-1 transition-transform duration-300 animate-float">
              <div className="flex justify-between items-start mb-4">
                <h3 className="font-semibold text-lg">Blood Stats</h3>
                <Users className="text-blood h-5 w-5" />
              </div>
              <div className="space-y-4">
                <div className="space-y-1">
                  <div className="flex justify-between">
                    <span className="text-sm text-muted-foreground">A+</span>
                    <span className="text-sm font-medium">27%</span>
                  </div>
                  <div className="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                    <div className="bg-blood h-full rounded-full" style={{ width: '27%' }} />
                  </div>
                </div>
                <div className="space-y-1">
                  <div className="flex justify-between">
                    <span className="text-sm text-muted-foreground">O+</span>
                    <span className="text-sm font-medium">39%</span>
                  </div>
                  <div className="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                    <div className="bg-blood h-full rounded-full" style={{ width: '39%' }} />
                  </div>
                </div>
                <div className="space-y-1">
                  <div className="flex justify-between">
                    <span className="text-sm text-muted-foreground">B+</span>
                    <span className="text-sm font-medium">25%</span>
                  </div>
                  <div className="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                    <div className="bg-blood h-full rounded-full" style={{ width: '25%' }} />
                  </div>
                </div>
              </div>
            </div>

            {/* Location Card */}
            <div className="absolute top-1/4 right-0 glass rounded-3xl p-6 w-64 shadow-lg transform hover:-translate-y-1 transition-transform duration-300" style={{ animationDelay: '0.2s' }}>
              <div className="flex justify-between items-start mb-4">
                <h3 className="font-semibold text-lg">Nearest Center</h3>
                <MapPin className="text-blood h-5 w-5" />
              </div>
              <div className="space-y-2">
                <p className="font-medium">City Blood Bank</p>
                <p className="text-sm text-muted-foreground">123 Main St, Downtown</p>
                <div className="flex items-center text-sm text-muted-foreground mt-2">
                  <Clock className="h-4 w-4 mr-2" />
                  <span>Open until 7:00 PM</span>
                </div>
                <div className="bg-gray-100 text-xs font-medium rounded-full px-3 py-1 inline-block mt-2">
                  2.5 km away
                </div>
              </div>
            </div>

            {/* Blood Drop Component */}
            <div className="absolute bottom-0 left-1/4 glass rounded-3xl p-6 w-72 shadow-lg transform hover:-translate-y-1 transition-transform duration-300 animate-float" style={{ animationDelay: '0.4s' }}>
              <div className="relative h-40 flex items-center justify-center">
                <svg className="w-32 h-32" viewBox="0 0 100 100">
                  <path 
                    d="M50,90 C70,90 85,75 85,58 C85,35 50,10 50,10 C50,10 15,35 15,58 C15,75 30,90 50,90 Z" 
                    fill="#e63946" 
                    className="animate-pulse-soft"
                  />
                </svg>
                <div className="absolute inset-0 flex items-center justify-center text-white font-bold text-xl">
                  A+
                </div>
              </div>
              <div className="text-center mt-4">
                <p className="font-medium">Your blood type can save lives</p>
                <p className="text-sm text-muted-foreground mt-1">One donation can save up to three lives</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Bottom Wave */}
      <div className="absolute bottom-0 left-0 right-0">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 160" className="w-full">
          <path 
            fill="currentColor" 
            fillOpacity="0.03" 
            d="M0,128L48,112C96,96,192,64,288,64C384,64,480,96,576,96C672,96,768,64,864,48C960,32,1056,32,1152,48C1248,64,1344,96,1392,112L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
          ></path>
        </svg>
      </div>
    </div>
  );
};

export default Hero;
