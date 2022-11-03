package com.example.powerstatus;

import static java.security.AccessController.getContext;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.app.ActivityCompat;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.location.Location;
import android.location.LocationManager;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;
import android.Manifest;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationCallback;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationResult;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.tasks.OnSuccessListener;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

import im.delight.android.location.SimpleLocation;

public class AppStartActivity extends AppCompatActivity {
    private static final int PERMISSIONS_FINE_LOCATION = 99;
    private SimpleLocation location;
    //google Api for application
    FusedLocationProviderClient fusedLocationProviderClient;
    // location Request
    LocationRequest locationRequest;
    LocationCallback locationCallback;




    TextView mtxtview, mtxtview4, loc;
    Button logout, rLocation;
    private int mInterval = 8000;
    int count = 0;
    String lat ="NotValid";
    String lon ="NotValid";
    String website ;
    Handler mHandler;
    SharedPreferences sharedPreferences;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        website = getResources().getString(R.string.website);
        AlertDialog.Builder builder = new AlertDialog.Builder(AppStartActivity.this);

        builder.setTitle("Confirm Location Change");
        builder.setMessage("This location is Used to link you with a power sensor , is this your home location?");

        builder.setPositiveButton("YES", new DialogInterface.OnClickListener() {

            public void onClick(DialogInterface dialog, int which) {
                // Do nothing but close the dialog
                String userN = sharedPreferences.getString("userName", "");
                recordLocation(userN,lat,lon);
                dialog.dismiss();
            }
        });

        builder.setNegativeButton("NO", new DialogInterface.OnClickListener() {

            @Override
            public void onClick(DialogInterface dialog, int which) {

                // Do nothing
                dialog.dismiss();
            }
        });

        locationRequest = new LocationRequest();
        locationRequest.setInterval(30000);
        locationRequest.setFastestInterval(5000);
        locationRequest.setPriority(LocationRequest.PRIORITY_BALANCED_POWER_ACCURACY);
        locationCallback = new LocationCallback() {
            @Override
            public void onLocationResult(LocationResult locationResult) {
                super.onLocationResult(locationResult);

                Location location = locationResult.getLastLocation();
                updateUIValues(location);
            }
        };


        setContentView(R.layout.activity_app_start);
        sharedPreferences = getSharedPreferences("UserInfo", MODE_PRIVATE);

        mtxtview = findViewById(R.id.Usern);
        mtxtview4 = findViewById(R.id.cusfeed);
        String Pstatus = sharedPreferences.getString("pStatus", "");
        String Cstatus = sharedPreferences.getString("cStatus", "");

        if (Pstatus.equals("OFF")) {
            mtxtview4.setText(Cstatus);
            mtxtview4.setVisibility(View.VISIBLE);
            if (Cstatus == null || Cstatus.length() < 2) {
                Cstatus = "Reported";
                mtxtview4.setText(Cstatus);
            } else {

                mtxtview4.setText(Cstatus);
            }


        }

        Toolbar toolbar = findViewById(R.id.toolbar);
        //setSupportActionBar(toolbar);
        // getSupportActionBar().setTitle("Power Status App");
        //getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        String userN = sharedPreferences.getString("userName", "");
        mtxtview.setText("UserName: "+userN);
        mHandler = new Handler();
        startRepeatingTask();
        logout = findViewById(R.id.logout);
        rLocation = findViewById(R.id.rLocation);


        AppStartActivity state = AppStartActivity.this;
        logout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                SharedPreferences.Editor editor = sharedPreferences.edit();
                editor.putString("loginstate", "loggedout");
                editor.apply();
                startActivity(new Intent(AppStartActivity.this, MainActivity.class));
                finish();
            }
        });
        updateGps();

        rLocation.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                AlertDialog alert = builder.create();
                alert.show();
                loc = findViewById(R.id.locstatus);
                loc.setText("Location check ");


            }
        });

    } //end of onCreate Method

    private void updateGps() {
        fusedLocationProviderClient = LocationServices.getFusedLocationProviderClient(AppStartActivity.this);
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED) {
            fusedLocationProviderClient.getLastLocation().addOnSuccessListener(this, new OnSuccessListener<Location>() {
                @Override
                public void onSuccess(Location location) {
                    if (location == null) {
                        loc = findViewById(R.id.locstatus);
                        loc.setText("Location not found ");
                        if (ActivityCompat.checkSelfPermission(AppStartActivity.this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(AppStartActivity.this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                            // TODO: Consider calling
                            //    ActivityCompat#requestPermissions
                            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                                requestPermissions(new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, PERMISSIONS_FINE_LOCATION);
                            }
                            return;
                        }
                        fusedLocationProviderClient.requestLocationUpdates(locationRequest, locationCallback, null);
                        updateGps();
                    } else {
                        updateUIValues(location);
                    }

                }
            });

        } else {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                requestPermissions(new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, PERMISSIONS_FINE_LOCATION);
            }
        }


    }

    private void updateUIValues(Location location) {
        String Latituded, Longitude;

        loc = findViewById(R.id.locstatus);

       lat = Double.toString(location.getLatitude());
        lon = Double.toString(location.getLongitude());
        loc.setText(new StringBuilder().append("Current location : ").append(lat).append(" ").append(lon).toString());

    }


    @Override
    public void onDestroy() {
        super.onDestroy();
        stopRepeatingTask();
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        switch (requestCode) {
            case PERMISSIONS_FINE_LOCATION:
                if (grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    updateGps();

                } else {
                    Toast.makeText(this, "This app requires the permission", Toast.LENGTH_SHORT).show();
                }
        }
    }

    Runnable mStatusChecker = new Runnable() {
        @Override
        public void run() {
            try {
                sharedPreferences = getSharedPreferences("UserInfo", MODE_PRIVATE);
                String userN = sharedPreferences.getString("userName", "");
                count = count + 1;
                if (count==2)
                {
                    startLocationupdates();

                }
                TextView mtxtview2 = findViewById(R.id.countsN);
                String zcount = String.valueOf(count);
                mtxtview2.setText(zcount);
                checkStatus(userN);


            } finally {
                mHandler.postDelayed(mStatusChecker, mInterval);
            }
        }
    };

    void startRepeatingTask() {
        mStatusChecker.run();
    }

    void stopRepeatingTask() {
        mHandler.removeCallbacks(mStatusChecker);
    }

    private void startLocationupdates() {
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            // TODO: Consider calling
            //    ActivityCompat#requestPermissions
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                requestPermissions(new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, PERMISSIONS_FINE_LOCATION);
            }
            return;
        }
        fusedLocationProviderClient.requestLocationUpdates(locationRequest, locationCallback, null);
        updateGps();
    }
    private void checkStatus(String username)
    {


        String url= website+"/app/checkStatus.php";
        StringRequest request= new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                String userN= "";
                String status= "";
                String cusfeed= "";
                try {
                    JSONObject ob = new JSONObject(response);
                    userN= ob.getString("userN");
                    status=ob.getString("status");
                    cusfeed=ob.getString("cusfeed");

                } catch (JSONException e) {
                    e.printStackTrace();
                }


                /* Toast.makeText(AppStartActivity.this,userN,Toast.LENGTH_SHORT).show(); */
                    SharedPreferences.Editor editor=sharedPreferences.edit();
                    editor.putString("pStatus",status);
                    editor.putString("cStatus",cusfeed);
                    editor.apply();
                TextView mtxtview3=findViewById(R.id.pstatus);
                TextView mtxtview4=findViewById(R.id.cusfeed);

                String userV ="OFF";
                if (status.equals("OFF"))
                {
                    mtxtview4.setText(cusfeed);
                    mtxtview4.setVisibility(View.VISIBLE);
                    if (cusfeed==null||cusfeed.length()<2||cusfeed.equals("null"))
                    {
                        cusfeed="Reported";
                        mtxtview4.setText(cusfeed);
                    }
                    else{

                        mtxtview4.setText(cusfeed);
                    }


                }
                if (status.equals("ON"))
                {

                    mtxtview4.setVisibility(View.GONE);
                }
                if (!status.equals("Not yet allocated to sensor"))
                {

                   rLocation.setText("Change My Location");

                }

                mtxtview3.setText(status);



            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(AppStartActivity.this,error.toString(),Toast.LENGTH_SHORT).show();
            }
        }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                HashMap<String, String> param = new HashMap<>();
                param.put("username", username);
                return param;
            }
        };
        request.setRetryPolicy(new DefaultRetryPolicy(3000,DefaultRetryPolicy.DEFAULT_MAX_RETRIES,DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        MySingleton.getmInstance(AppStartActivity.this).addToRequestQueue(request);
    }
    private void recordLocation(String username, String lat, String lon ){
        ProgressDialog progressDialog = new ProgressDialog(AppStartActivity.this);
        progressDialog.setCancelable(false);
        progressDialog.setIndeterminate(false);
        progressDialog.setTitle("Recording Location ");
        progressDialog.show();
        String url= website+"/app/rLocate.php";
        StringRequest request= new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {

            @Override
            public void onResponse(String response) {
                if (response.equals("Successfully Registered")){


                    progressDialog.dismiss();
                    Toast.makeText(AppStartActivity.this,response,Toast.LENGTH_SHORT).show();


                }
                else
                {
                    progressDialog.dismiss();
                    Toast.makeText(AppStartActivity.this, response ,Toast.LENGTH_SHORT).show();

                }


            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                progressDialog.dismiss();
                Toast.makeText(AppStartActivity.this, error.toString() ,Toast.LENGTH_SHORT).show();
            }
        }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                HashMap<String,String > param = new HashMap<>();
                param.put("lat",lat);
                param.put("lon",lon);
                param.put("username",username);

                return param;
            }
        };
        request.setRetryPolicy(new DefaultRetryPolicy(3000,DefaultRetryPolicy.DEFAULT_MAX_RETRIES,DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        MySingleton.getmInstance(AppStartActivity.this).addToRequestQueue(request);




    }




}
