package com.example.powerstatus;

import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.rengwuxian.materialedittext.MaterialEditText;

import java.util.HashMap;
import java.util.Map;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class MainActivity extends AppCompatActivity  {

    MaterialEditText email,password;
    Button login,register;
    CheckBox loginstate ;
    SharedPreferences sharedPreferences;
    String website;





    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        website = getResources().getString(R.string.website);
        sharedPreferences =getSharedPreferences("UserInfo", Context.MODE_PRIVATE);
        email=findViewById(R.id.email);
        password=findViewById(R.id.password);
        loginstate=findViewById(R.id.loginstate);
        register=findViewById(R.id.register);
        login=findViewById(R.id.login);

        register.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(MainActivity.this,RegisterActivity.class));
            }
        });
        login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String txtEmail = email.getText().toString();
                String txtPassword = password.getText().toString();
                if(TextUtils.isEmpty(txtEmail) ||TextUtils.isEmpty(txtPassword))
                {
                    Toast.makeText(MainActivity.this,"Please fill out the fields ",Toast.LENGTH_SHORT).show();
                }
                else
                {
                    login(txtEmail,txtPassword);
                }

            }
        });
        String loginstatus=sharedPreferences.getString("loginstate","");


        if(loginstatus.equals("loggedin") )
        {
            startActivity(new Intent(MainActivity.this,AppStartActivity.class));

        }


    }
    private void login(String email,String password )
    {
        ProgressDialog progressDialog = new ProgressDialog(MainActivity.this);
        progressDialog.setCancelable(false);
        progressDialog.setIndeterminate(false);
        progressDialog.setTitle("Logging in ");
        progressDialog.show();

        String url= website+"/app/login.php";
        StringRequest request= new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                String userN= "";
                String respo= "";
                try {
                    JSONObject  ob = new JSONObject(response);
                    userN= ob.getString("userN");
                    respo=ob.getString("respon");

                } catch (JSONException e) {
                    e.printStackTrace();
                }

                if(respo.equals("Login Successfull")){
                    Toast.makeText(MainActivity.this,response,Toast.LENGTH_SHORT).show();
                    SharedPreferences.Editor editor=sharedPreferences.edit();
                    editor.putString("userName",userN);
                    editor.putString("pStatus"," ");
                    editor.putString("cStatus"," ");
                    editor.apply();

                    if(loginstate.isChecked()) {

                        editor.putString("loginstate","loggedin");
                    }
                    else
                        {
                            editor.putString("loginstate","loggedout");
                        }
                    editor.apply();

                    startActivity(new Intent(MainActivity.this,AppStartActivity.class));
                }
                else
                {
                    progressDialog.dismiss();
                    Toast.makeText(MainActivity.this,response,Toast.LENGTH_SHORT).show();

                }

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                progressDialog.dismiss();
                Toast.makeText(MainActivity.this,error.toString(),Toast.LENGTH_SHORT).show();
            }
        }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                HashMap<String, String> param = new HashMap<>();
                param.put("email", email);
                param.put("password", password);
                return param;
            }
        };
        request.setRetryPolicy(new DefaultRetryPolicy(3000,DefaultRetryPolicy.DEFAULT_MAX_RETRIES,DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        MySingleton.getmInstance(MainActivity.this).addToRequestQueue(request);
    }
}