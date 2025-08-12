#include<iostream.h>
#include<conio.h>
#include<stdio.h>

void main()
{
    int x1,x2,x3,s,b[4],c1[5],c2[5],c3[5],i;
    char opt[5],e[5];
    char o1[5],o2[5];
    clrscr();
    cout<<"\n Enter the optimality Either MIN or MAX: ";
    gets(opt);
    cout<<"\n Enter the values for optimality function: ";
    cout<<"\n Enter the values for x1, x2, x3: ";
    cin>>x1>>x2>>x3;
    cout<<"\n Enter the number of constraints: ";
    cin>>s;
    e[s],b[s],c1[s],c2[s],c3[s];
    o1[s],o2[s];
    for(i =0 ;i<s;i++)
    {
	  cout<<"\nEnter the x1, operator2, x2, operator3, x3, < (or) = (or) > and b: ";
	  cin>>c1[i]>>o1[i]>>c2[i]>>o2[i]>>c3[i]>>e[i]>>b[i];
    }
    cout<<"\n optimality Function\n";
    if(opt=="max"||opt=="MAX")
    {
	    cout<<"MAX"<<"(Z) = "<<x1<<"x1 +"<<x2<<"x2 +"<<x3<<"x3";
    }else
    {
	    cout<<"MIN"<<"(Z) = "<<x1<<"x1 +"<<x2<<"x2 +"<<x3<<"x3";
    }
    cout<<"\n Subject to constraints";
    for(i=0;i<s;i++)
    {
      if(e[i]=='=')
      {
       cout<<"\n"<<c1[i]<<"x1 "<<o1[i]<<c2[i]<<"x2 "<<o2[i]<<c3[i]<<"x3 "<<e[i]<<b[i];
       }
      else
      {
       cout<<"\n"<<c1[i]<<"x1 "<<o1[i]<<c2[i]<<"x2 "<<o2[i]<<c3[i]<<"x3 "<<e[i]<<"="<<b[i];
      }
    }
    cout<<"\n non-negative restriction\n x1, x2, x3 >= 0";
    if(opt=="min"||opt=="MIN")
    {
	cout<<"\nThis is a General form of lpp";
    }
    else
    {
    for(i=0;i<s;i++)
    {
     if(s==2)
     {
	if(e[i]=='<'&&e[i+1]=='<')
  {
	    cout<<"\nThis is canonical form of lpp";
		   break;
	      }
	      else if(e[i]=='='&&e[i+1]=='=')
        {
		 cout<<"\n This is standard form of lpp";
		    break;
	      }
		    else
        {
			cout<<"\n This is General form of lpp";
			break;n
	      }
	    }
     if(s==3)
     {
	  if(e[i]=='<'&&e[i+1]=='<'&&e[i+2]=='<')
    {
	    cout<<"\nThis is canonical form of lpp";
		    break;
	   }
	      else if(e[i]=='='&&e[i+1]=='='&&e[i+2]=='=')
        {
		 cout<<"\n This is standard form of lpp";
		    break;
	     }
		    else
        {
			cout<<"\n This is General form of lpp";
		    break;
	    }
	}
     }
  }
    getch();
}

OUTPUT:
Enter the optimality Either MIN or MAX: min                                    
                                                                                
 Enter the values for optimality function:                                      
 Enter the values for x1, x2, x3: 1                                             
2                                                                               
3                                                                               
                                                                                
 Enter the number of constraints: 2
                                                                                
Enter the x1, operator2, x2, operator3, x3, < (or) = (or) > and b: 1 + 2 + 3 < 4
                                                                                
                                                                                
Enter the x1, operator2, x2, operator3, x3, < (or) = (or) > and b: 5 + 3 + 2 > 1
                                                                                
                                                                                
optimality Function                                                             
MIN(Z) = 1x1 +2x2 +3x3                                                          
Subject to constraints                                                         
1x1 +2x2 +3x3 <=4                                                               
5x1 +3x2 +2x3 >=1                                                               
non-negative restriction                                                        
x1, x2, x3 >= 0                                                                
This is General form of lpp    
#include<iostream.h>
#include<conio.h>
#include<stdio.h>

void main()
{
    int x1,x2,x3,s,b[4],c1[5],c2[5],c3[5],i;
    char opt[5],e[5];
    char o1[5],o2[5];
    clrscr();
    cout<<"\n Enter the optimality Either MIN or MAX: ";
    gets(opt);
    cout<<"\n Enter the values for optimality function: ";
    cout<<"\n Enter the values for x1, x2, x3: ";
    cin>>x1>>x2>>x3;
    cout<<"\n Enter the number of constraints: ";
    cin>>s;
    e[s],b[s],c1[s],c2[s],c3[s];
    o1[s],o2[s];
    for(i =0 ;i<s;i++){
	  cout<<"\nEnter the x1, operator2, x2, operator3, x3, < (or) = (or) > and b: ";
	  cin>>c1[i]>>o1[i]>>c2[i]>>o2[i]>>c3[i]>>e[i]>>b[i];
    }
    cout<<"\noptimality Function\n";
    if(opt=="max"||opt=="MAX"){
	    cout<<"MAX"<<"(Z) = "<<x1<<"x1 +"<<x2<<"x2 +"<<x3<<"x3";
    }else{
	    cout<<"MIN"<<"(Z) = "<<x1<<"x1 +"<<x2<<"x2 +"<<x3<<"x3";
    }
    cout<<"\n Subject to constraints";
    for(i=0;i<s;i++){
      if(e[i]=='='){
       cout<<"\n"<<c1[i]<<"x1 "<<o1[i]<<c2[i]<<"x2 "<<o2[i]<<c3[i]<<"x3 "<<e[i]<<b[i];
       }
      else{
       cout<<"\n"<<c1[i]<<"x1 "<<o1[i]<<c2[i]<<"x2 "<<o2[i]<<c3[i]<<"x3 "<<e[i]<<"="<<b[i];
      }
    }
    cout<<"\nnon-negative restriction\n x1, x2, x3 >= 0";
    if(opt=="min"||opt=="MIN"){
	cout<<"\nThis is a General form of lpp";
    }
    else{
    for(i=0;i<s;i++){
     if(s==2){
	if(e[i]=='<'&&e[i+1]=='<'){
	    cout<<"\nThis is canonical form of lpp";
		   break;
	      }
	      else if(e[i]=='='&&e[i+1]=='='){
		 cout<<"\n This is standard form of lpp";
		    break;
	      }
		    else{
			cout<<"\n This is General form of lpp";
			break;
	      }
	    }
     if(s==3){
	  if(e[i]=='<'&&e[i+1]=='<'&&e[i+2]=='<'){
	    cout<<"\nThis is canonical form of lpp";
		    break;
	   }
	      else if(e[i]=='='&&e[i+1]=='='&&e[i+2]=='='){
		 cout<<"\n This is standard form of lpp";
		    break;
	     }
		    else{
			cout<<"\n This is General form of lpp";
		    break;
	    }
	}
     }
  }
    getch();
}

OUTPUT:
Enter the optimality Either MIN or MAX: min                                    
                                                                                
 Enter the values for optimality function:                                      
 Enter the values for x1, x2, x3: 1                                             
2                                                                               
3                                                                               
                                                                                
 Enter the number of constraints: 2
                                                                                
Enter the x1, operator2, x2, operator3, x3, < (or) = (or) > and b: 1 + 2 + 3 < 4
                                                                                
                                                                                
Enter the x1, operator2, x2, operator3, x3, < (or) = (or) > and b: 5 + 3 + 2 > 1
                                                                                
                                                                                
optimality Function                                                             
MIN(Z) = 1x1 +2x2 +3x3                                                          
Subject to constraints                                                         
1x1 +2x2 +3x3 <=4                                                               
5x1 +3x2 +2x3 >=1                                                               
non-negative restriction                                                        
x1, x2, x3 >= 0                                                                
This is General form of lpp    
